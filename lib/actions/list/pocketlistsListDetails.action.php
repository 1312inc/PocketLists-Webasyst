<?php

class pocketlistsListDetailsAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::post('id', false, waRequest::TYPE_INT);
        $im = new pocketlistsListModel();
        if ($id) {
            $list = $im->getList($id);
            $user_name  = new waContact($list['contact_id']);
            $list['username'] = $user_name->getName();
            $this->view->assign('list', $list);

            // get icons
            $this->view->assign('list_icons', waFiles::listdir(wa()->getAppPath('img/listicons/', wa()->getApp())));
        }
    }
}