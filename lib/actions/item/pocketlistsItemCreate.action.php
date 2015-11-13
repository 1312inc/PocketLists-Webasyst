<?php

class pocketlistsItemCreateAction extends waViewAction
{
    public function execute()
    {
        $data = waRequest::post('data', false, waRequest::TYPE_ARRAY);
        $list_id = waRequest::post('list_id', false, waRequest::TYPE_INT);

        $im = new pocketlistsItemModel();
        $items = array();
        if ($list_id && $data) {
            if (!is_array($data)) {
                $data = array($data);
            }
            foreach ($data as $i => $d) {
                $data[$i]['create_datetime'] = date("Y-m-d H:i:s");
                $data[$i]['list_id'] = $list_id;

                $inserted = $im->insert($data[$i], 1);
                $items[] = array('id' => $inserted) + $data[$i];
            }
        }
        $this->view->assign('items', $items);
    }
}