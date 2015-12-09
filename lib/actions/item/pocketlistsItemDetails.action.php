<?php

class pocketlistsItemDetailsAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::post('id', false, waRequest::TYPE_INT);
        if ($id) {
            $im = new pocketlistsItemModel();
            $lm = new pocketlistsListModel();
            $item = $im->getById($id);

            $list = $lm->getById($item['list_id']);
            // get contact that have access to this pocket
            $contacts = pocketlistsHelper::getAccessContactsForPocket($list['pocket_id']);

            $this->view->assign('item', $item);
            $this->view->assign('contacts', $contacts);
        }
    }
}