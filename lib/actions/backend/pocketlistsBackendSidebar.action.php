<?php

class pocketlistsBackendSidebarAction extends waViewAction
{
    public function execute()
    {
        $im = new pocketlistsItemModel();
        $todo = $im->getToDo();
        $this->view->assign('sidebar_todo_count', count($todo[0]));

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