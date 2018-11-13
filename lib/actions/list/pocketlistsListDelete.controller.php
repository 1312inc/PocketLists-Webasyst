<?php

class pocketlistsListDeleteController extends waJsonController
{
    public function execute()
    {
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_ARRAY);

        if ($list_id) {
            $lm = new pocketlistsListModel();
            $list = $lm->getById($list_id);

            if ($list && $lm->remove($list_id)) {
                $this->response = 'ok';

                // log this action
                $this->logAction(pocketlistsLogAction::LIST_DELETED, array('list_name' => $list['name']));
            } else {
                $this->errors = 'error while deleting list and it items';
            }
        }
    }
}
