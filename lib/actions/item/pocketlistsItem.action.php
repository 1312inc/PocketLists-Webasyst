<?php

class pocketlistsItemAction extends waViewAction
{
    public function execute()
    {
        $item_id = waRequest::get('id', false, waRequest::TYPE_INT);
        $data = waRequest::post('data', false, waRequest::TYPE_ARRAY);

        $im = new pocketlistsItemModel();
        if ($data) {
            if (!$item_id) {
                $data['create_datetime'] = date("Y-m-d H:i:s");
                unset($data['id]']);
            };
            $inserted = $im->insert($data, 1);
            if ($inserted instanceof waDbResult) {
                $item = $inserted;
            } else {
                $item = array('id' => $inserted) + $data;
            }
        } else {
            $item = $im->getById($item_id);
        }
        $this->view->assign('item', $item);
    }
}