<?php

class pocketlistsListUpdateController extends waJsonController
{
    public function execute()
    {
        $list_id = waRequest::post('id', false, waRequest::TYPE_INT);
        $data = waRequest::post('data', false, waRequest::TYPE_ARRAY);

        $lm = new pocketlistsListModel();
        if ($list_id > 0) {
            $data['id'] = $list_id;
        } else {
            $data['create_datetime'] = date("Y-m-d H:i:s");
        }
        $data['contact_id'] = wa()->getUser()->getId();
        if ($inserted = $lm->insert($data, 1)) {
            pocketlistsNotifications::notifyAboutNewList($data + array('id' => $inserted));
        }

        $this->response = array('id' => $inserted);
    }
}