<?php

/**
 * Class pocketlistsAppDateAction
 */
class pocketlistsAppDateAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws pocketlistsForbiddenException
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function runAction($params = null)
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

        $itemsUndone = $itemFactory->findUndoneForApp($app, '', 0, [$date]);

        $itemsDone = $itemFactory
            ->setLimit(pocketlistsFactory::DEFAULT_LIMIT)
            ->setOffset(0)
            ->findDoneForApp($app, '', 0, [$date]);

        $countDoneItems = pl2()->getModel(pocketlistsItem::class)->countAppItems(
            $app->getApp(),
            '',
            0,
            [$date],
            pocketlistsItem::STATUS_DONE
        );

        $this->view->assign(
            [
                'undone_items'     => $itemsUndone,
                'done_items'       => $itemsDone,
                'count_done_items' => $countDoneItems,
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
                'itemAdd'              => (new pocketlistsItemAddAction())->display(false),
            ]
        );
    }
}
