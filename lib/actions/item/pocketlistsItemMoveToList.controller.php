<?php

/**
 * Class pocketlistsItemMoveToListController
 */
class pocketlistsItemMoveToListController extends pocketlistsJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_INT);

        $item = $this->getItem();

        if (!$list_id) {
            $this->errors = 'no list id';

            return;
        }

        if ($item->getListId() == $list_id) {
            $this->errors = 'same list';

            return;
        }

        /** @var pocketlistsList $list */
        $list = pl2()->getEntityFactory(pocketlistsList::class)
            ->findById($list_id);

        if (!$list) {
            $this->errors = 'no list';

            return;
        }

        if (!pocketlistsRBAC::canAccessToList($list)) {
            $this->errors = 'no access';

            return;
        }

        $item
            ->setList($list)
            ->setSort(0);

        $updated = pl2()->getEntityFactory(pocketlistsItem::class)->save($item);

        // todo: childs??
        if ($updated) {
            $listItems = $list->getUndoneItems();
            $curPos = 1;
            $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
            /** @var pocketlistsItem $listItem */
            foreach ($listItems as $listItem) {
                if ($listItem->getId() === $item->getId()) {
                    continue;
                }

                // todo: this should be one update
                $listItem->setSort($curPos++);
                $itemFactory->save($listItem, ['sort']);
            }

            $this->response = $item->getId();
        } else {
            $this->errors = 'db error';
        }
    }
}
