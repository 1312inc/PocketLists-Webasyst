<?php

class pocketlistsListEmailDialogAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::get('id', false, waRequest::TYPE_INT);
        if ($id) {
            $lm = new pocketlistsListModel();
            $im = new pocketlistsItemModel();
            $list = $lm->getById($id);

            $this->view->assign('list', $list);
            $this->view->assign('items', $im->getUndoneByList($list['id']));
        }
    }

}
