<?php

/**
 * Class pocketlistsTodoMonthAction
 */
class pocketlistsTodoMonthAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waDbException
     * @throws waException
     */
    public function runAction($params = null)
    {
        $timezone = wa()->getUser()->getTimezone();
        $show_month = waRequest::get('month', 0, waRequest::TYPE_INT);

        // get pocket dots
        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);
        // get all due or priority or assigned to me items
        $items = $itemModel->getToDo(wa()->getUser()->getId(), pocketlistsHelper::getMonthBounds($show_month));

        $monthData = pocketlistsHelper::getMonthData($items, $show_month);

        $this->view->assign(
            [
                'days' => $monthData['days'],

                'week_first_sunday' => waLocale::getFirstDay() === 7,
                'current_month'     => date('n', $monthData['month_date']),
                'current_year'      => date('Y', $monthData['month_date']),
                'prev_month'        => date('Y-m', strtotime('-1 month', $monthData['month_date'])),
                'next_month'        => date('Y-m', strtotime('+1 month', $monthData['month_date'])),

                // cast to user timezone
                'today'             => waDateTime::date('j', null, $timezone),
                'today_month'       => waDateTime::date('n', null, $timezone),

                'type' => 'todo',
            ]
        );
    }
}
