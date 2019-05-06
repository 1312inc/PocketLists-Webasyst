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

        if (!pocketlistsRBAC::canAccessToList($list)) {
            throw new pocketlistsForbiddenException();
        }

        $items = $list->getItems();
        $items = (new pocketlistsStrategyItemFilterAndSort($items))->filterDoneUndone()->getProperSortUndone();

        pl2()->getEntityFactory(pocketlistsItem::class)->updateProperSort($items);
    }
}
