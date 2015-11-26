<?php

class pocketlistsListDeleteController extends waJsonController
{
    public function execute()
    {
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_INT);
        $archive = waRequest::post('archive', 0, waRequest::TYPE_INT);

        if ($list_id) {
            $lm = new pocketlistsListModel();

            if ($lm->updateById($list_id, array('archived' => $archive))) {
                $this->response = 'ok';
            } else {
                $this->errors = 'error while deleting list and his items';
            }
        }
    }
}