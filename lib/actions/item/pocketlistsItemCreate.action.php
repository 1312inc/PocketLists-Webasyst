<?php

class pocketlistsItemCreateAction extends waViewAction
{
    public function execute()
    {
        $data = waRequest::post('data', false, waRequest::TYPE_ARRAY);
        $list_id = waRequest::post('list_id', false, waRequest::TYPE_INT);

        $im = new pocketlistsItemModel();
        $inserted = array();
        $user_id = wa()->getUser()->getId();
        if ($list_id && $data) {
            if (!is_array($data)) {
                $data = array($data);
            }
            foreach ($data as $i => $d) {
                $data[$i]['create_datetime'] = date("Y-m-d H:i:s");
                $data[$i]['list_id'] = $list_id;
                $data[$i]['contact_id'] = $user_id;

                $inserted[] = $im->insert($data[$i], 1);
            }
        }
        $this->view->assign('items', $im->getByField('id', $inserted, true));
    }
}