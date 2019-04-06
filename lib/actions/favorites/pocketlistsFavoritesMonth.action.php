<?php

/**
 * Class pocketlistsFavoritesMonthAction
 */
class pocketlistsFavoritesMonthAction extends pocketlistsViewAction
{
    /**
     * @throws waException
     */
    public function execute()
    {
        $timezone = wa()->getUser()->getTimezone();
        $show_month = waRequest::get('month', 0, waRequest::TYPE_INT);

        $month_date = date('Y-m', strtotime(date('Y-m-01').' '.($show_month.' month')));

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
        /** @var pocketlistsItem[] $items */
        $items = $itemFactory->findFavoritesForUserAndDate($this->user, $month_date);

        $filteredItems = (new pocketlistsStrategyItemFilterAndSort($items))->filterDoneUndone();

        $monthData = pocketlistsHelper::getMonthData($filteredItems, $show_month);

        $this->view->assign(
            [
                'days'              => $monthData['days'],
                'week_first_sunday' => waLocale::getFirstDay() === 7,
                'current_month'     => date('n', $monthData['month_date']),
                'current_year'      => date('Y', $monthData['month_date']),
                'prev_month'        => date('Y-m', strtotime('-1 month', $monthData['month_date'])),
                'next_month'        => date('Y-m', strtotime('+1 month', $monthData['month_date'])),
                'today'             => waDateTime::date('j', null, $timezone),         // cast to user timezone

                'today_month' => waDateTime::date('n', null, $timezone),         // cast to user timezone

                'type' => 'favorites',
            ]
        );

        $this->setTemplate('templates/include/monthcalendar.html');
    }
}
