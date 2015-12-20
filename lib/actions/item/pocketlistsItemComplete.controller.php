<?php

class pocketlistsItemCompleteController extends pocketlistsComplete
{
    public function execute()
    {
        $id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $status = waRequest::post('status', 0, waRequest::TYPE_INT);

        $im = new pocketlistsItemModel();

        if ($id > 0) { // complete item/items
            $item = $im->getById($id);
            if ($item['has_children']) {
                $tree = $im->getAllByList($item['list_id'], $id);
                $this->changeComplete($item['id'], $tree[$item['id']], $status, $im);
            } else {
                $this->changeComplete($item['id'], $item + array('childs' => array()), $status, $im);
            }
            pocketlistsNotifications::notifyAboutCompleteItems($this->completed_items);

            $this->response = $id;
        } else {
            $this->errors = 'no id';
        }
    }
}