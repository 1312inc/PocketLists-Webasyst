<?php

/**
 * Class pocketlistsItemDataAction
 */
class pocketlistsItemDataAction extends pocketlistsViewItemAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws pocketlistsLogicException
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function runAction($params = null)
    {
        $item_new_data = waRequest::request('item', [], waRequest::TYPE_ARRAY);
        $isNewItem = false;

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);

        /** @var pocketlistsItem $item */
        if (!empty($item_new_data['id'])) {
            $item = $this->getItem($item_new_data['id']);
            $itemOld = clone $item;
        } else {
            $item = $itemFactory->createNew();
            $isNewItem = true;
        }

        if ($item_new_data && waRequest::getMethod() === 'post') {
//            $item->setListId($item_new_data['list_id'] === '' ? null : $item_new_data['list_id']);

            // todo: childs not available for now
            // move item's children to another list
//            $move_ids = [];
            /*if ($item_from_db['has_children'] && $item_from_db['list_id'] != $item_new_data['list_id']) {
                $tree = $im->getAllByList($item_from_db['list_id'], true);

                pocketlistsHelper::getItemChildIds($item_new_data['id'], $tree[$item_new_data['id']], $move_ids);

                $im->updateById(
                    $move_ids,
                    [
                        'list_id'         => $item_new_data['list_id'],
                        'update_datetime' => date("Y-m-d H:i:s"),
                    ]
                );
            }*/

            pocketlistsHelper::getDueDatetime($item_new_data);

            $oldAssignedId = !empty($item_new_data['id']) ? $item->getAssignedContactId() : 0;

            $item = pl2()->getHydrator()->hydrate($item, $item_new_data);

            if ($item->getId()) {
                $item->setUpdateDatetime(date('Y-m-d H:i:s'));
            } else {
                $item
                    ->setContactId($this->user->getContact()->getId())
                    ->setCreateDatetime(date('Y-m-d H:i:s'));
            }

            if ($item->getListId()) {
                if (!pocketlistsRBAC::canAccessToList($item->getList())) {
                    throw new waException('Access denied.', 403);
                }

                if ($item->getAssignedContactId()
                    && !pocketlistsRBAC::canAccessToList($item->getList(), $item->getAssignedContactId())) {
                    $item->setAssignedContactId(null);
                }
            }

            $saved = $itemFactory->save($item);
            if ($saved) {
                if ($item->getAssignedContactId()) {
                    $us = new pocketlistsUserSettings($item->getAssignedContactId());
                    // settings are set AND assigned id is updated
                    if ($us->emailWhenNewAssignToMe() && $oldAssignedId != $item->getAssignedContactId()) {
                        (new pocketlistsNotificationAboutNewAssign())->notify($item);
                    }
                }
            }

            if ($item->getAssignedContactId() != $oldAssignedId) {
                $this->logAction(
                    pocketlistsLogAction::ITEM_ASSIGN,
                    [
                        'list_id'     => $item->getListId(),
                        'item_id'     => $item->getId(),
                        'assigned_to' => $item->getAssignedContactId(),
                    ]
                );
            }

            if (!empty($item_new_data['links_delete'])) {
                /** @var pocketlistsItemLinkFactory $itemLinkFactory */
                $itemLinkFactory = pl2()->getEntityFactory(pocketlistsItemLink::class);

                $itemsToDelete = $itemLinkFactory->findById($item_new_data['links_delete']);

                /** @var pocketlistsItemLink $link */
                foreach ($itemsToDelete as $link) {
                    if ($link->getItemId() == $item->getId()) {
                        $itemLinkFactory->delete($link);
                    }
                }
            }

            if (!empty($item_new_data['links'])) {
                /** @var pocketlistsItemLinkFactory $itemLinkFactory */
                $itemLinkFactory = pl2()->getEntityFactory(pocketlistsItemLink::class);

                foreach ($item_new_data['links'] as $link) {
                    $linkData = $link['model'];

                    $itemLinkFactory->createFromDataForItem($item, $linkData);
                }
            }

            $context = (new pocketlistsLogContext())
                ->setList($item->getList())
                ->setItem($item);

            if ($item->getAssignedContactId() != $oldAssignedId) {
                $context->addParam(['item_action' => 'new assign']);
            }

            if ($isNewItem) {
                $this->logService->add(
                    $this->logService->getFactory()->createNewAfterItemAdd($context)
                );

                if ($item->getList()) {
                    $this->logAction(
                        pocketlistsLogAction::NEW_ITEM,
                        [
                            'item_id' => $item->getId(),
                            'list_id' => $item->getList()->getId(),
                        ]
                    );
                }
            } else {
                $this->logService->add(
                    $this->logService->getFactory()->createNewAfterItemUpdate($context)
                );
            }
        }

        $this->view->assign(
            'pl2_attachments_path',
            wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID)
        );

        $this->view->assign('item', $item);
    }
}
