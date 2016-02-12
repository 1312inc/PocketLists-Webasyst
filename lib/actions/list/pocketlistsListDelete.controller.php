<?php

class pocketlistsListDeleteController extends waJsonController
{
    public function execute()
    {
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_ARRAY);

        if ($list_id) {
            $lm = new pocketlistsListModel();

            if ($lm->delete($list_id)) {
                $this->response = 'ok';

                // log this action
                $this->logAction('list_deleted');
            } else {
                $this->errors = 'error while deleting list and it items';
            }
        }
    }
}
