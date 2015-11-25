<?php

class pocketlistsItemDetailsAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::post('id', false, waRequest::TYPE_INT);
        $im = new pocketlistsItemModel();
        if ($id) {
            $item = $im->getById($id);
            $this->view->assign('item', $item);
        }
    }
}