<?php

class pocketlistsItemDetailsAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::post('id', false, waRequest::TYPE_INT);
        $im = new pocketlistsItemModel();
        if ($id) {
            $this->view->assign('item', $im->getById($id));
        }
    }
}