<?php

class pocketlistsItemCreateAction extends waViewAction
{
    public function execute()
    {
        $data = waRequest::post('data', false, waRequest::TYPE_ARRAY);
        $list_id = waRequest::post('list_id', false, waRequest::TYPE_INT);

        $im = new pocketlistsItemModel();
        $inserted = $inserted_items = array();
        $user_id = wa()->getUser()->getId();
        if ($list_id && $data) {
            if (!is_array($data)) {
                $data = array($data);
            }
            $lm = new pocketlistsListModel();
            $list = $lm->getById($list_id);
            foreach ($data as $i => $d) {
                $data[$i]['name'] = nl2br(strip_tags($data[$i]['name']));
                $data[$i]['create_datetime'] = date("Y-m-d H:i:s");
                $data[$i]['list_id'] = $list['id'];
                $data[$i]['contact_id'] = $user_id;

                $last_id = $im->insert($data[$i], 1);
                $inserted[] = $last_id;
                $inserted_items[] = $data[$i] + array('id' => $last_id);
            }
            pocketlistsNotifications::notifyAboutNewItems($inserted_items, $list);
        }
        $this->view->assign('items', $im->getByField('id', $inserted, true));
    }
}