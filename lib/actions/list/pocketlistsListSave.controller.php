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
            $data = $im->update($list['id'], $list);
            if ($data) {
                if ($data['due_date']) {
                    $data['due_date'] = waDateTime::format('humandate', $data['due_date']);
                }
                if ($data['due_datetime']) {
                    $data['due_datetime'] = waDateTime::format('humandatetime', $data['due_datetime']);
                }
                $this->response = $data;
            } else {
                $this->errors = 'error while saving item';
            }
        }
    }
}