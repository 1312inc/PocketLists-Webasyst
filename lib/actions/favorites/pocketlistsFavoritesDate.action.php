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
        $items = $itemFactory->findFavoritesForUserAndDate($this->user, $date);

        $filter = new pocketlistsStrategyItemFilterAndSort($items);
        $itemsUndone = $filter->filterDoneUndone()->getProperSortUndone();
        $itemsDone = $filter->getItemsDone();

        $timestamp = $date ? waDateTime::date('Y-m-d', strtotime($date)) : '';

        $this->view->assign(
            [
                'undone_items'         => $itemsUndone,
                'done_items'           => $itemsDone,
                'count_done_items'     => count($itemsDone),
                'date'                 => $date,
                'timestamp'            => $timestamp,
                'pl2_attachments_path' => wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID),
                'this_is_stream'       => true,
                'print'                => waRequest::get('print', false),
                'user'                 => $this->user,
            ]
        );
    }
}
