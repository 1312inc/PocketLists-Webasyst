<?php

class pocketlistsListDeleteController extends waJsonController
{
    public function execute()
    {
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_ARRAY);

        if ($list_id) {
            $im = new pocketlistsItemModel();
            $lm = new pocketlistsListModel();

            $delete_item_ids = array();
            $lists = $im->getAllByList($list_id);
            foreach ($lists as $i) {
                $delete_item_ids[] = $i['id'];
            }
            if ($im->deleteById($delete_item_ids) && $lm->deleteById($list_id) ) {
                $this->response = 'ok';
            } else {
                $this->errors = 'error while deleting list and his items';
            }
        }
    }
}