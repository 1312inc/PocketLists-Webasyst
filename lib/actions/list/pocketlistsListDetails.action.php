<?php

class pocketlistsListDetailsAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::post('id', false, waRequest::TYPE_INT);
        $im = new pocketlistsListModel();
        if ($id) {
            $list = $im->getById($id);
            $user_name  = new waContact($list['contact_id']);
            $list['contact'] = pocketlistsHelper::getContactData($user_name);
            $this->view->assign('list', $list);
        }
    }
}
