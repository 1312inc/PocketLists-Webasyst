<?php

class pocketlistsListUpdateController extends waJsonController
{
    public function execute()
    {
        $list_id = waRequest::post('id', false, waRequest::TYPE_INT);
        $data = waRequest::post('data', false, waRequest::TYPE_ARRAY);

        $lm = new pocketlistsListModel();
        if ($list_id) {
            $data['id'] = $list_id;
        } else {
            $data['create_datetime'] = date("Y-m-d H:i:s");
        }
        $inserted = $lm->insert($data, 1);

        $this->response = array('id' => $inserted);
    }
}