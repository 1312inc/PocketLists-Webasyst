<?php

class pocketlistsListSaveController extends waJsonController
{
    public function execute()
    {
        $list = waRequest::post('list', array(), waRequest::TYPE_ARRAY);

        if ($list) {
            $im = new pocketlistsListModel();
            pocketlistsHelper::getDueDatetime($list);

            $list['update_datetime'] = date("Y-m-d H:i:s");
            $list['contact_id'] = wa()->getUser()->getId();
            if ($im->update($list['id'], $list)) {
                $this->response = 'ok';
            } else {
                $this->errors = 'error while saving item';
            }
        }
    }
}