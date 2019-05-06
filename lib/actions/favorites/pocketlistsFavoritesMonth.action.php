<?php

/**
 * Class pocketlistsFavoritesMonthAction
 */
class pocketlistsFavoritesMonthAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waException
     */
    public function runAction($params = null)
    {
        $timezone = wa()->getUser()->getTimezone();
        $show_month = waRequest::get('month', 0, waRequest::TYPE_INT);

        $month_date = new DateTime(date('Y-m-01'));
        $month_date->modify($show_month.' month');
        $monthStart = $month_date->format('Y-m-d');

        $month_date->modify('+1 month')->modify('-1 day');
        $monthEnd = $month_date->format('Y-m-d');

        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);
        $items = $itemModel->getFavorites($this->user->getId(), $monthStart, $monthEnd);

        $monthData = pocketlistsHelper::getMonthData($items, $show_month);

        $this->view->assign(
            [
                'days'              => $monthData['days'],
                'week_first_sunday' => waLocale::getFirstDay() === 7,

                'current_month' => date('n', $monthData['month_date']),
                'current_year'  => date('Y', $monthData['month_date']),
                'prev_month'    => date('Y-m', strtotime('-1 month', $monthData['month_date'])),
                'next_month'    => date('Y-m', strtotime('+1 month', $monthData['month_date'])),

                'today'       => waDateTime::date('j', null, $timezone),         // cast to user timezone
                'today_month' => waDateTime::date('n', null, $timezone),         // cast to user timezone

                'type' => 'favorites',
            ]
        );
    }
}
