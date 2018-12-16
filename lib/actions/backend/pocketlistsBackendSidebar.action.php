<?php

/**
 * Class pocketlistsBackendSidebarAction
 */
class pocketlistsBackendSidebarAction extends pocketlistsViewAction
{
    /**
     * @throws waDbException
     */
    public function execute()
    {
        $im = new pocketlistsItemModel();
        $todo = $im->getToDo();
        $this->view->assign('sidebar_todo_count', count($todo[0]));

        $list_model = new pocketlistsListModel();
        $this->view->assign('lists', $list_model->getLists());

        /** @var pocketlistsTeammateFactory $factory */
        $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getModelFactory('Teammate');
        $teammates = $factory->getTeammates(pocketlistsRBAC::getAccessContacts(), true, true, true);
        foreach ($teammates as $tid => $teammate) {
            if (!$teammate['id']) {
                unset($teammates[$tid]);
            }
        }
        $this->view->assign('team', $teammates);

        $last_activity = pocketlistsActivity::getUserActivity();

        $comment_model = new pocketlistsCommentModel();
        $item_model = new pocketlistsItemModel();

        $new_items_count = $item_model->getLastActivityItems($last_activity);
        $this->view->assign('new_comments_count', $comment_model->getLastActivityComments($last_activity));
        $this->view->assign('new_items_count', $new_items_count);
        $this->view->assign('last_activity', $last_activity);

        $item_model = new pocketlistsItemModel();
        $this->view->assign('favorites_count', $item_model->getFavoritesCount());

//        pocketlistsActivity::setUserActivity();

        $pockets = pocketlistsPocketModel::model()->getAllPockets();
        $linkedApps = wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedApp();

        $this->view->assign(compact('pockets', 'linkedApps'));
    }
}
