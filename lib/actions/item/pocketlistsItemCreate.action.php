<?php

/**
 * Class pocketlistsItemCreateAction
 */
class pocketlistsItemCreateAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waDbException
     * @throws waException
     */
    public function runAction($params = null)
    {
        $data = waRequest::post('data', false, waRequest::TYPE_ARRAY);
        $filter = waRequest::post('filter', false);
        $list_id = waRequest::post('list_id', false, waRequest::TYPE_INT);
        $assigned_contact_id = waRequest::post('assigned_contact_id', false, waRequest::TYPE_INT);

        $inserted = $items = [];
        $assign_contact = null;
        $user_id = wa()->getUser()->getId();
        $plContact = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId($user_id);

        $canAssign = pocketlistsRBAC::canAssign();
        if ($canAssign && $assigned_contact_id) {
            $assign_contact = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId($assigned_contact_id);
        }

        if ($data) {
            $paste = false;
            if (!is_array($data)) {
                $data = [$data];
            } else {
                $paste = true;
            }

            /** @var pocketlistsListFactory $listFactory */
            $listFactory = pl2()->getEntityFactory(pocketlistsList::class);
            /** @var pocketlistsItemFactory $itemFactory */
            $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);

            $list = $list_id ? $listFactory->findById($list_id) : $listFactory->createNewNullList();

            foreach ($data as $i => $d) {
                if (!empty($d['list_id']) && $list_id != $d['list_id']) {
                    $list = $listFactory->findById($d['list_id']);
                }

                pocketlistsHelper::getDueDatetime($d);

                /** @var pocketlistsItem $item */
                $item = $itemFactory->createNew();
                $item = pl2()->getHydrator()->hydrate($item, $d);
                $item
                    ->setCreateDatetime(date('Y-m-d H:i:s'))
                    ->setList($list)
                    ->setContact($this->user);

                if ($canAssign && ($assigned_contact_id || !empty($d['assigned_contact_id']))) {
                    if (!empty($d['assigned_contact_id'])) {
                        $assign_contact = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId($d['assigned_contact_id']);
                    }

                    if ($assign_contact->isExists()) {
                        $item->setAssignedContact($assign_contact);
                    }
                }

                if (!empty($d['due_date'])) {
                    $item->setDueDate(waDateTime::date('Y-m-d', strtotime($d['due_date'])));
                }

                $item->setName(trim($d['name']));

                // if add throught paste - cut some letters
                if ($paste) {
                    $item->setName(preg_replace('/^(([-|—|–]*)(\s*))(.*)$/u', "$4", $item->getName()));
                }

                // natural input parse
                $ni = pocketlistsNaturalInput::matchPriority($item->getName());
                if ($ni) {
                    $item->setName($ni['name']);
                    $item->setPriority((int)$ni['priority']);
                }

                $ni = pocketlistsNaturalInput::matchNote($item->getName());
                if ($ni) {
                    $item->setName($ni['name']);
                    $item->setNote($ni['note']);
                }

                if (!$item->getDueDate() && $this->user->getSettings()->getNaturalInput()) {
                    $name = $item->getName();
                    $ni = pocketlistsNaturalInput::matchDueDate($name);
                    if ($ni) {
                        $item->setDueDate($ni['due_date']);
                        if ($ni['due_datetime']) {
                            $tm = pocketlistsHelper::convertToServerTime(strtotime($ni['due_datetime']));
                            $item->setDueDatetime(waDateTime::date('Y-m-d H:i:s', $tm));
                        }

                        $item->setName($name);
                    }
                }

                $item->recalculatePriority();
                $itemFactory->insert($item);

                /** @var pocketlistsItemLinkFactory $itemLinkFactory */
                $itemLinkFactory = pl2()->getEntityFactory(pocketlistsItemLink::class);
                $links = pocketlistsNaturalInput::getLinks($item->getName());
                if ($links) {
                    $linkDeterminer = new pocketlistsLinkDeterminer();
                    foreach ($links as $link) {
                        $linkAppTypeId = $linkDeterminer->getAppTypeId($link);
                        if ($linkAppTypeId === false) {
                            continue;
                        }

                        $itemLinkFactory->createFromDataForItem($item, $linkAppTypeId);
                    }
                }
                if (!empty($d['links'])) {
                    foreach ($d['links'] as $link) {
                        $linkData = $link['model'];

                        $itemLinkFactory->createFromDataForItem($item, $linkData);
                    }
                }

                if (!empty($d['files'])) {
                    /** @var pocketlistsFactory $attachmentFactory */
                    $attachmentFactory = pl2()->getEntityFactory(pocketlistsAttachment::class);
                    $attachments = [];
                    foreach (explode('|~|', $d['files']) as $file) {
                        $uploadedFile = pocketlistsUploadedFileVO::createTempFromName($file);
                        $uploadedPath = $uploadedFile->getFullPath();
                        $uploadedFile->setItemId($item->getId());
                        waFiles::move($uploadedPath, $uploadedFile->getFullPath());

                        /** @var pocketlistsAttachment $attachment */
                        $attachment = $attachmentFactory->createNew();
                        $attachment
                            ->setFilename($uploadedFile->getName())
                            ->setFiletype($uploadedFile->getType())
                            ->setItemId($item->getId());
                        $attachmentFactory->insert($attachment);
                        $attachments[] = $attachment;

                        $this->logService->add(
                            $this->logService->getFactory()->createNewAttachmentLog(
                                (new pocketlistsLogContext())
                                    ->setItem($item)
                                    ->setAttachment($attachment)
                            )
                        );
                    }
                    $item->setAttachments($attachments);
                }

                $inserted[] = $item->getId();
                $items[] = $item;
            }

            if ($inserted) {
                switch ($filter) {
                    case 'favorites':
                        /** @var pocketlistsItem $item */
                        foreach ($items as $item) {
                            $item->makeFavorite($plContact);
                        }
                        break;
                }

                pl2()->getEventDispatcher()->dispatch(
                    new pocketlistsEventItemsSave(
                        pocketlistsEventStorage::ITEM_INSERT,
                        $items,
                        [
                            'list' => $list,
                            'assign_contact_id' => $assign_contact instanceof pocketlistsContact ? $assign_contact->getId() : 0
                        ]
                    )
                );
            }
        }

        $this->view->assign('items', $items);

        $externalApp = waRequest::request('external_app', null, waRequest::TYPE_STRING_TRIM);
        $template = pl2()->getUI2TemplatePath('templates/actions%s/item/Item.html', $externalApp);

        $this->setTemplate($template);
    }
}
