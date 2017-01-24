<?php

class pocketlistsBackendSidebarAction extends waViewAction
{
    public function execute()
    {
        $this->view->assign('sidebar_todo_count', wa('pocketlists')->getConfig()->onCount());

        $list_model = new pocketlistsListModel();
        $this->view->assign('lists', $list_model->getAllActiveLists());

        $teammates = array();
        if ($this->getRights('canassign')) {
            $teammates_ids = pocketlistsHelper::getAllListsContacts();

            $teammates = pocketlistsHelper::getTeammates($teammates_ids);
        }
        $this->view->assign('team', $teammates);
    }
}