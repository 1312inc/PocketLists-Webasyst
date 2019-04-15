<?php

/**
 * Class pocketlistsItemCreateAction
 */
class pocketlistsItemCreateAction extends pocketlistsViewAction
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
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

        // if no list id passed - get default list from settings
        if (!$list_id) {
            $us = new pocketlistsUserSettings($assign_contact ? $assign_contact->getId() : $user_id);
            $list_id = $us->getStreamInboxList();
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
                /** @var pocketlistsItem $item */
                $item = $itemFactory->createNew();
                $item
                    ->setCreateDatetime(date('Y-m-d H:i:s'))
                    ->setList($list)
                    ->setContactId($user_id);

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

                if ($this->user->getSettings()->getNaturalInput()) {
                    $name = $item->getName();
                    $ni = pocketlistsNaturalInput::matchDueDate($name);
                    if ($ni) {
                        $item->setDueDate($ni['due_date']);
                        if ($ni['due_datetime']) {
                            $tm = pocketlistsHelper::convertToServerTime(strtotime($ni['due_datetime']));
                            $item->setDueDatetime(waDateTime::date('Y-m-d H:i:s', $tm));
                        }
                    }
                }

                $item->recalculatePriority();
                $itemFactory->insert($item);

                $links = pocketlistsNaturalInput::getLinks($item->getName());

                /** @var pocketlistsItemLinkFactory $itemLinkFactory */
                $itemLinkFactory = pl2()->getEntityFactory(pocketlistsItemLink::class);

                if ($links) {
                    $linkDeterminer = new pocketlistsLinkDeterminer();
                    foreach ($links as $link) {
                        $linkAppTypeId = $linkDeterminer->getAppTypeId($link);
                        if ($linkAppTypeId === false) {
                            continue;
                        }

                        // todo:
                        /** @var pocketlistsItemLink $itemLink */
                        $itemLink = $itemLinkFactory->createNew();
                        $itemLink
                            ->setApp($linkAppTypeId['app'])
                            ->setEntityId($linkAppTypeId['entity_id'])
                            ->setEntityType($linkAppTypeId['entity_type'])
                            ->setItem($item);

                        try {
                            $itemLinkFactory->save($itemLink);
                            $item->addAppLinks($itemLink);
                        } catch (waException $ex) {
                            // silence
                        }
                    }
                }

                if (!empty($d['links'])) {
                    foreach ($d['links'] as $link) {
                        /** @var pocketlistsAppLinkInterface $app */
                        $app = pl2()->getLinkedApp($link['model']['app']);

                        if (!$app->userCanAccess()) {
                            continue;
                        }

                        foreach ($link['model'] as $key => $value) {
                            if ($value === '') {
                                $link['model'][$key] = null;
                            }
                        }

                        /** @var pocketlistsItemLink $itemLink */
                        $itemLink = $itemLinkFactory->createNew();
                        $itemLink
                            ->setApp($link['model']['app'])
                            ->setEntityId($link['model']['entity_id'])
                            ->setEntityType($link['model']['entity_type'])
                            ->setItem($item);

                        try {
                            $itemLinkFactory->save($itemLink);
                            $item->addAppLinks($itemLink);
                        } catch (waException $ex) {
                            // silence
                        }
                    }
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

                (new pocketlistsNotificationAboutNewItems())->notify($items, $list);

                // log this action
                foreach ($items as $item) {
                    if ($assign_contact) {
                        (new pocketlistsNotificationAboutNewAssign())->notify($item, $list);

                        $this->logAction(
                            pocketlistsLogAction::ITEM_ASSIGN_TEAM,
                            [
                                'list_id'     => $item->getListId(),
                                'item_id'     => $item->getId(),
                                'assigned_to' => $item->getAssignedContactId(),
                            ]
                        );
                    }
                }

                if ($list->getId()) {
                    $this->logAction(pocketlistsLogAction::NEW_ITEMS, ['list_id' => $list->getId()]);
                }
            }
        }

        $this->view->assign('items', $items);

        $this->setTemplate('templates/actions/item/Item.html');
    }
}
