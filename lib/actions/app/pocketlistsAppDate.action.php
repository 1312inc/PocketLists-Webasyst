<?php

/**
 * Class pocketlistsAppDateAction
 */
class pocketlistsAppDateAction extends pocketlistsViewAction
{
    /**
     * @throws pocketlistsForbiddenException
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function execute()
    {
        $app_id = waRequest::get('app');

        if (!$app_id) {
            throw new pocketlistsNotFoundException();
        }

        /** @var pocketlistsAppLinkInterface $app */
        $app = pl2()->getLinkedApp($app_id);

        if (!$app->userCanAccess()) {
            throw new pocketlistsForbiddenException();
        }

        $date = waRequest::get('date', false);
        $filter = waRequest::get('filter', false);
        $timestamp = $date ? waDateTime::date('Y-m-d', strtotime($date)) : '';

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
        $items = $itemFactory->findAllForApp($app, '', 0, $date);

        $filterItems = (new pocketlistsStrategyItemFilterAndSort($items))->filterDoneUndone();

        $this->view->assign(
            [
                'undone_items'     => $filterItems->getItemsUndone(),
                'done_items'       => $filterItems->getItemsDone(),
                'count_done_items' => count($filterItems->getItemsDone()),
                'date'             => $date,
                'timestamp'        => $timestamp,
            ]
        );

        $this->view->assign(
            [
                'filter'               => $filter,
                'pl2_attachments_path' => wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID),
                'this_is_stream'       => true,
                'print'                => waRequest::get('print', false),
                'app'                  => $app,
                'user'                 => $this->user,
            ]
        );
    }
}
