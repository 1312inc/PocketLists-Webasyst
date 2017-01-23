<?php

class pocketlistsBackendSidebarAction extends waViewAction
{
    public function execute()
    {
        $this->view->assign('sidebar_todo_count', wa('pocketlists')->getConfig()->onCount());

        $list_model = new pocketlistsListModel();
        $this->view->assign('lists', $list_model->getAllActiveLists());

        $team = array();
        if ($this->getRights('canassign')) {
            $team = pocketlistsHelper::getAccessContactsForList();
        }
        $this->view->assign('team', $team);
    }
}