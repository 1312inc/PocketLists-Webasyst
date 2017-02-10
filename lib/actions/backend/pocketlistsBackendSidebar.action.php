<?php

class pocketlistsBackendSidebarAction extends waViewAction
{
    public function execute()
    {
        $this->view->assign('sidebar_todo_count', wa('pocketlists')->getConfig()->onCount());

        $list_model = new pocketlistsListModel();
        $this->view->assign('lists', $list_model->getLists());

        $teammates = pocketlistsHelper::getTeammates(pocketlistsRBAC::getAccessContacts());
        $this->view->assign('team', $teammates);

        $last_activity = pocketlistsActivity::getUserActivity();

        $comment_model = new pocketlistsCommentModel();

        $this->view->assign('new_comments_count', $comment_model->getLastActivityComments($last_activity));
        $this->view->assign('last_activity', $last_activity);

        $item_model = new pocketlistsItemModel();
        $this->view->assign('favorites_count', $item_model->getFavoritesCount());

//        pocketlistsActivity::setUserActivity();
    }
}