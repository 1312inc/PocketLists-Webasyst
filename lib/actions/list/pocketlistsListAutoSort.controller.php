<?php

class pocketlistsListAutoSortController extends waJsonController
{
    public function execute()
    {
        $list_id = waRequest::post('list_id', false);

        if ($list_id) {
            $lm = new pocketlistsListModel();
            $list = $lm->getById($list_id);

            if (!in_array($list['pocket_id'], pocketlistsHelper::getAccessPocketForContact())) {
                throw new waRightsException('403');
            }

            $im = new pocketlistsItemModel();
            $im->sortItems($list['id']);
        }
    }
}
