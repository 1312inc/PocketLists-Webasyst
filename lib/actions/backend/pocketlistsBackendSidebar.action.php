<?php

/**
 * Class pocketlistsBackendSidebarAction
 */
class pocketlistsBackendSidebarAction extends pocketlistsViewAction
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
        $items = $itemFactory->findToDo($this->user);

        $sidebar_todo_count = (new pocketlistsStrategyItemFilterAndSort($items))->filterDoneUndone()->countUndone();
        $sidebar_todo_count_icon = pl2()->getUser()->getAppCount();

        $this->view->assign(compact('sidebar_todo_count', 'sidebar_todo_count_icon'));

        /** @var pocketlistsListFactory $pocketFactory */
        $pocketFactory = pl2()->getEntityFactory(pocketlistsList::class);
        $this->view->assign('lists', $pocketFactory->findAllActive());

        /** @var pocketlistsContactFactory $contactFactory */
        $contactFactory = pl2()->getEntityFactory(pocketlistsContact::class);
        $teammates = $contactFactory->getTeammates(pocketlistsRBAC::getAccessContacts(), true, true, true);

        foreach ($teammates as $tid => $teammate) {
            if (!$teammate->getId()) {
                unset($teammates[$tid]);
            }
        }
        $this->view->assign('team', $teammates);

        $last_activity = pocketlistsActivity::getUserActivity();

        /** @var pocketlistsCommentModel $commentModel */
        $commentModel = pl2()->getModel(pocketlistsComment::class);

        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);

        $this->view->assign(
            [
                'new_comments_count' => $commentModel->getLastActivityComments($last_activity),
                'new_items_count'    => $itemModel->getLastActivityItems($last_activity),
                'last_activity'      => $last_activity,
                'favorites_count'    => $itemModel->getFavoritesCount(),
            ]
        );

        /** @var pocketlistsPocketFactory $pocketFactory */
        $pocketFactory = pl2()->getEntityFactory(pocketlistsPocket::class);
        $pockets = $pocketFactory->getAllPocketsForUser();

        /** @var pocketlistsPocket $pocket */
        foreach ($pockets as $pocketId => $pocket) {
            if (!pocketlistsRBAC::contactHasAccessToPocket($pocket)) {
                unset($pockets[$pocketId]);
            }
        }
        $linkedApps = pl2()->getLinkedApp();

        $this->view->assign(compact('pockets', 'linkedApps'));
    }
}
