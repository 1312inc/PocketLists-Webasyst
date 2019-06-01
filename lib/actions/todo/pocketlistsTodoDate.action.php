<?php

/**
 * Class pocketlistsTodoDateAction
 */
class pocketlistsTodoDateAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waException
     */
    public function runAction($params = null)
    {
        $date = waRequest::get('date', false);
        $filter = waRequest::get('filter', false);

        // get all due or priority or assigned to me items

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);

        $itemsUndone = $itemFactory->findToDoUndone($this->user, $date);
        $itemsDone = $itemFactory
            ->setOffset(0)
            ->setLimit(pocketlistsFactory::DEFAULT_LIMIT)
            ->findToDoDone($this->user, $date);

        $countDoneItems = pl2()->getEntityCounter()
            ->countTodoItems(
                $this->user,
                [],
                pocketlistsItem::STATUS_DONE,
                $date ? [$date] : []
            );

        $this->view->assign(
            [
                'undone_items'     => $itemsUndone,
                'done_items'       => $itemsDone,
                'count_done_items' => $countDoneItems->getCount(),
                'date'             => $date,
                'timestamp'        => $date ? waDateTime::date('Y-m-d', strtotime($date)) : '',

                'filter'               => $filter,
                'pl2_attachments_path' => wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID),
                'this_is_stream'       => true,
                'print'                => waRequest::get('print', false),
                'user'                 => $this->user,
            ]
        );
    }
}
