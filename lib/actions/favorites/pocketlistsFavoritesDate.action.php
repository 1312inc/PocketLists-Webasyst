<?php

/**
 * Class pocketlistsFavoritesDateAction
 */
class pocketlistsFavoritesDateAction extends pocketlistsViewAction
{
    // todo: almost same as ToDo
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waException
     */
    public function runAction($params = null)
    {
        $date = waRequest::get('date', false);

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);

        $itemsUndone = $itemFactory->findFavoritesUndoneForUserAndDate($this->user, $date);
        $itemsDone = $itemFactory
            ->setLimit(pocketlistsFactory::DEFAULT_LIMIT)
            ->setOffset(pocketlistsFactory::DEFAULT_OFFSET)
            ->findFavoritesDoneForUserAndDate($this->user, $date);

        $countItemsDone = pl2()->getEntityCounter()->countFavoritesItems(
            $this->user,
            $date,
            false,
            pocketlistsItem::STATUS_DONE
        )->getCount();

        $timestamp = $date ? waDateTime::date('Y-m-d', strtotime($date)) : '';

        $this->view->assign(
            [
                'undone_items'         => $itemsUndone,
                'done_items'           => $itemsDone,
                'count_done_items'     => $countItemsDone,
                'date'                 => $date,
                'timestamp'            => $timestamp,
                'pl2_attachments_path' => wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID),
                'this_is_stream'       => true,
                'print'                => waRequest::get('print', false),
                'user'                 => $this->user,
                'itemAdd'              => (new pocketlistsItemAddAction())->display(false),
            ]
        );
    }
}
