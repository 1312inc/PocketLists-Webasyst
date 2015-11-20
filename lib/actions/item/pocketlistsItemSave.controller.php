<?php

class pocketlistsItemSaveController extends waJsonController
{
    public function execute()
    {
        $item = waRequest::post('item', array(), waRequest::TYPE_ARRAY);

        if ($item) {
            $im = new pocketlistsItemModel();
            $item['due_date'] = $item['due_date'] ? @waDateTime::format('Y-m-d', strtotime($item['due_date'])) : null;
            $item['update_datetime'] = date("Y-m-d H:i:s");
            if ($im->updateById($item['id'], $item)) {
                $this->response = 'ok';
            } else {
                $this->errors = 'error while saving item';
            }
        }
    }
}