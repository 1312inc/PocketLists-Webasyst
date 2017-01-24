<?php

class pocketlistsListSortController extends waJsonController
{
    public function execute()
    {
        if (!wa()->getUser()->isAdmin() && !wa()->getUser()->isAdmin('pocketlists')) {
            throw new waRightsException('403');
        }

        $data = waRequest::post('data', false);

        if ($data) {
            $lm = new pocketlistsListModel();
            foreach ($data as $list) {
                $lm->updateById($list['id'], array('sort' => $list['sort']));
            }
        }
    }
}
