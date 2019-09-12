<?php

/**
 * Class pocketlistsBackendSidebarAction
 */
class pocketlistsBackendSidebarAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waDbException
     * @throws waException
     */
    public function runAction($params = null)
    {
        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);

        $sidebar_todo_count_icon = pl2()->getEntityCounter()->countTodoUndoneWithUserPrioritiesItems();
        $sidebar_todo_count = pl2()->getEntityCounter()->countTodoUndoneItems()->getCount();

        $this->view->assign(compact('sidebar_todo_count', 'sidebar_todo_count_icon'));

        /** @var pocketlistsListFactory $listFactory */
        $listFactory = pl2()->getEntityFactory(pocketlistsList::class);
        $lists = $listFactory->findAllActive();
        $this->view->assign('lists', $lists);

        /** @var pocketlistsContactFactory $contactFactory */
        $contactFactory = pl2()->getEntityFactory(pocketlistsContact::class);
        $teammates = $contactFactory->getTeammates(pocketlistsRBAC::getAccessContacts(), true, true, true);

        foreach ($teammates as $tid => $teammate) {
            if (!$teammate->isExists()) {
                unset($teammates[$tid]);
            }
        }
        $this->view->assign('team', $teammates);

        $last_activity = pocketlistsActivity::getUserActivity();

        /** @var pocketlistsCommentModel $commentModel */
        $commentModel = pl2()->getModel(pocketlistsComment::class);

        $favoritesCount = pl2()->getEntityCounter()->countFavoritesItems(
            $this->user,
            false,
            false,
            pocketlistsItem::STATUS_UNDONE
        );
        $this->view->assign(
            [
                'new_comments_count'     => $commentModel->getLastActivityComments($last_activity),
                'new_items_count'        => $itemModel->getLastActivityItems($last_activity),
                'last_activity'          => $last_activity,
                'favorites_count_undone' => $favoritesCount,
            ]
        );

        /** @var pocketlistsPocketFactory $pocketFactory */
        $pocketFactory = pl2()->getEntityFactory(pocketlistsPocket::class);
        $pockets = $pocketFactory->findAllForUser();

        $linkedApps = pl2()->getLinkedApp();
        foreach ($linkedApps as $i => $app) {
            if (!$app->userCanAccess()) {
                unset($linkedApps[$i]);
            }
        }

        $this->view->assign(compact('pockets', 'linkedApps'));
        $this->view->assign(
            [
                'backend_sidebar' => pl2()->waDispatchEvent(new pocketlistsEvent(pocketlistsEventStorage::WA_BACKEND_SIDEBAR)),
                'isAdmin'         => $this->getUser()->isAdmin('pocketlists'),
            ]
        );
    }
}
