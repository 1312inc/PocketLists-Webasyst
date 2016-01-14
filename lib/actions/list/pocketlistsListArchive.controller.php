<?php

class pocketlistsListArchiveController extends waJsonController
{
    public function execute()
    {
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_INT);
        $archive = waRequest::post('archive', null, waRequest::TYPE_INT);

        if ($list_id) {
            $lm = new pocketlistsListModel();
            if ($lm->update($list_id, array('archived' => $archive))) {
                $this->response = 'ok';

                // log this action
                class_exists('waLogModel') || wa('webasyst');
                $log_model = new waLogModel();
                $log_model->add('list_archived', $list_id);
            } else {
                $this->errors = 'error while deleting list and his items';
            }
        }
    }
}
