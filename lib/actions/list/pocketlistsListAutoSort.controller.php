<?php

/**
 * Class pocketlistsListAutoSortController
 */
class pocketlistsListAutoSortController extends pocketlistsJsonController
{
    /**
     * @throws waDbException
     * @throws waException
     * @throws waRightsException
     */
    public function execute()
    {
        $list = $this->getList();

        if (!in_array($list->getId(), pocketlistsRBAC::getAccessListForContact())) {
            throw new pocketlistsForbiddenException();
        }

        $items = $list->getItems();
        $filter = new pocketlistsStrategyItemFilterAndSort($items);
        $items = $filter->getProperSort()->getItemsUndone();

        pl2()->getEntityFactory(pocketlistsItem::class)->updateProperSort($items);
    }
}
