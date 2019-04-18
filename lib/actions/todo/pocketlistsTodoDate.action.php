<?php

/**
 * Class pocketlistsTodoDateAction
 */
class pocketlistsTodoDateAction extends pocketlistsViewAction
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $date = waRequest::get('date', false);
        $filter = waRequest::get('filter', false);

        // get all due or priority or assigned to me items

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
        $items = $itemFactory->findToDo($this->user, $date);

        $itemFilter = (new pocketlistsStrategyItemFilterAndSort($items))->filterDoneUndone();

        $this->view->assign(
            [
                'undone_items'     => $itemFilter->getProperSortUndone(),
                'done_items'       => $itemFilter->getProperSortDone(),
                'count_done_items' => count($itemFilter->getItemsDone()),
                'date'             => $date,
                'timestamp'        => $date
                    ? waDateTime::date('Y-m-d', strtotime($date))
                    : waDateTime::date(
                        'Y-m-d',
                        time() + 60 * 60 * 24,
                        wa()->getUser()->getTimezone()
                    ),
            ]
        );

        $this->view->assign(
            [
                'filter'               => $filter,
                'pl2_attachments_path' => wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID),
                'this_is_stream'       => true,
                'print'                => waRequest::get('print', false),
                'user'                 => $this->user,
            ]
        );
    }
}
