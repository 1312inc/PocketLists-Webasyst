<?php

/**
 * Class pocketlistsAppMonthAction
 */
class pocketlistsAppMonthAction extends pocketlistsViewAction
{
    /**
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
            throw new pocketlistsNotFoundException();
        }

        $timezone = wa()->getUser()->getTimezone();
        $show_month = waRequest::get('month', 0, waRequest::TYPE_INT);

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);

        $items = $itemFactory->findAllForApp($app, false, false, $show_month);
        $filterItems = (new pocketlistsStrategyItemFilterAndSort($items))->filterDoneUndone();

        $monthData = pocketlistsHelper::getMonthData($filterItems, $show_month);

        $this->view->assign(
            [
                'days'              => $monthData['days'],
                'week_first_sunday' => waLocale::getFirstDay() === 7,
                'current_month'     => date('n', $monthData['month_date']),
                'current_year'      => date('Y', $monthData['month_date']),
                'prev_month'        => date('Y-m', strtotime('-1 month', $monthData['month_date'])),
                'next_month'        => date('Y-m', strtotime('+1 month', $monthData['month_date'])),
                // cast to user timezone
                'today'             => waDateTime::date('j', null, $timezone),
                'today_month'       => waDateTime::date('n', null, $timezone),
                'type'              => 'app',
                'app'               => $app,
            ]
        );

        $this->setTemplate('templates/include/monthcalendar.html');
    }
}
