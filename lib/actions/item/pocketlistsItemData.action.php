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

        $itemsOld = [];
        /** @var pocketlistsItem $item */
        if (!empty($item_new_data['id'])) {
            $item = $this->getItem($item_new_data['id']);
            pocketlistsAssert::instance($item, pocketlistsItem::class);

            $itemsOld[$item->getId()] = clone $item;
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
            if (!$saved) {
                throw new pocketlistsLogicException('Save item error');
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

            if ($isNewItem) {
                pl2()->getEventDispatcher()->dispatch(
                    new pocketlistsEventItemsSave(
                        pocketlistsEventStorage::ITEM_INSERT,
                        $item,
                        ['list' => $item->getList(), 'assign_contact_id' => $item->getAssignedContactId()]
                    )
                );
            } else {
                pl2()->getEventDispatcher()->dispatch(
                    new pocketlistsEventItemsSave(
                        pocketlistsEventStorage::ITEM_UPDATE,
                        $item,
                        [
                            'list' => $item->getList(),
                            'assign_contact_id' => $item->getAssignedContactId(),
                            'old_assign_contact_id' => $oldAssignedId,
                            'itemsOld' => $itemsOld,
                        ]
                    )
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
