<?php

/**
 * Class pocketlistsFavoritesDateAction
 */
class pocketlistsFavoritesDateAction extends pocketlistsViewAction
{
    // todo: almost same as ToDo
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $date = waRequest::get('date', false);

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
        $items = $itemFactory->findFavoritesForUserAndDate($this->user, $date);

        $filter = (new pocketlistsStrategyItemFilterAndSort())->filterDoneUndone($items);
        $itemsUndone = $filter->getProperSort($filter->getItemsUndone());
        $itemsDone = $filter->getItemsDone();

        $timestamp = $date
            ? waDateTime::date('Y-m-d', strtotime($date))
            : waDateTime::date('Y-m-d', time() + 60 * 60 * 24, wa()->getUser()->getTimezone());

        /** @var pocketlistsListFactory $listFactory */
        $listFactory = pl2()->getEntityFactory(pocketlistsList::class);
        $stream_list_id = pl2()->getUser()->getSettings()->getStreamInboxList();
        if ($stream_list_id && $stream_list = $listFactory->findById($stream_list_id)) {
            $this->view->assign(
                [
                    'stream_list_id' => $stream_list_id,
                    'stream_list'    => $stream_list,
                ]
            );
        }

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
