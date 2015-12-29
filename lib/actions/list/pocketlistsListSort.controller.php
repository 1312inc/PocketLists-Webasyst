<?php

class pocketlistsListSortController extends waJsonController
{
    public function execute()
    {
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_INT);

        if ($list_id) {

            $im = new pocketlistsItemModel();
            $items = $im->sortItems($list_id);
        }
    }
}
