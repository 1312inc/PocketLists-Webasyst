<?php

class pocketlistsListSortController extends waJsonController
{
    public function execute()
    {
        $data = waRequest::post('data', false);

        if ($data) {
            $lm = new pocketlistsListModel();
            foreach ($data as $list) {
                $lm->updateById($list['id'], array('sort' => $list['sort']));
            }
        }
    }
}
