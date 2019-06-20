<?php

/**
 * Class pocketlistsSearchActions
 */
class pocketlistsSearchAction extends pocketlistsViewAction
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
        $term = waRequest::request('term', waRequest::TYPE_STRING_TRIM, '');

        $itemSearch = (new pocketlistsSearchItems($term))->search();
        $listSearch = (new pocketlistsSearchLists($term))->search();

        $items = $itemSearch->getResults();
        $itemFilter = (new pocketlistsStrategyItemFilterAndSort($items))->filterDoneUndone();

        $this->view->assign(
            [
                'items_done'       => $itemFilter->getItemsDone(),
                'count_items_done' => $itemFilter->countDone(),
                'items'            => $itemFilter->getItemsUndone(),
                'items_count'      => $itemSearch->getFoundCount(),

                'lists'       => $listSearch->getResults(),
                'lists_count' => $listSearch->getFoundCount(),

                'fileupload' => true,
                'user'       => $this->user,
                'term'       => $term
            ]
        );

    }
}
