<?php

class pocketlistsBackendSidebarAction extends waViewAction
{
    public function execute()
    {
        $this->view->assign('sidebar_todo_count', wa('pocketlists')->getConfig()->onCount());

        $list_model = new pocketlistsListModel();
        $this->view->assign('lists', $list_model->getAllActiveLists());

        $teammates = pocketlistsHelper::getTeammates(pocketlistsRBAC::getAccessContacts());
        $this->view->assign('team', $teammates);

        $last_activity = pocketlistsActivity::getUserActivity();
        $pactions = new pocketlistsLogAction();
        $last_logs  = $pactions->getLogsForUser($last_activity);
        $this->view->assign('new_items_count', count($last_logs));
        $this->view->assign('last_activity', $last_activity);

        $item_model = new pocketlistsItemModel();
        $this->view->assign('favorites_count', $item_model->getFavoritesCount());

//        pocketlistsActivity::setUserActivity();
    }
}