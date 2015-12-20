<?php

class pocketlistsListCompleteController extends pocketlistsComplete
{
    public function execute()
    {
        $list_id = waRequest::post('list_id', 0, waRequest::TYPE_INT);
        $status = waRequest::post('status', 0, waRequest::TYPE_INT);

        $im = new pocketlistsItemModel();

        if ($list_id) { // complete all list items
            $tree = $im->getUndoneByList($list_id);
            $lm = new pocketlistsListModel();
            $list = $lm->getById($list_id);
            $this->changeComplete(0, array('id' => null, 'childs' => $tree), $status, $im);
            pocketlistsNotifications::notifyAboutCompleteItems($this->completed_items, $list);

            $this->response = $list_id;
        } else {
            $this->errors = 'no id';
        }
    }
}