<?php

class pocketlistsItemDetailsAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::post('id', false, waRequest::TYPE_INT);
        $im = new pocketlistsItemModel();
        if ($id) {
            $item = $im->getById($id);
            $user_name  = new waContact($item['contact_id']);
            $item['contact_name'] = $user_name->getName();
            $this->view->assign('item', $item);
        }
    }
}