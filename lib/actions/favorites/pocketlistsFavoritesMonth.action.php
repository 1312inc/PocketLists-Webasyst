<?php

class pocketlistsFavoritesMonthAction extends waViewAction
{
    public function execute()
    {
        $timezone = wa()->getUser()->getTimezone();
        $show_month = waRequest::get('month', 0, waRequest::TYPE_INT);

        // get pocket dots
        $im = new pocketlistsItemModel();
        // get all due or priority or assigned to me items
        $items = $im->getFavorites(wa()->getUser()->getId(), $show_month);

        $monthData = pocketlistsHelper::getMonthData($items, $show_month);

        $this->view->assign('days', $monthData['days']);

        $this->view->assign('week_first_sunday', waLocale::getFirstDay() === 7);
        $this->view->assign('current_month', date('n', $monthData['month_date']));
        $this->view->assign('current_year', date('Y', $monthData['month_date']));
        $this->view->assign('prev_month', date('Y-m', strtotime('-1 month', $monthData['month_date'])));
        $this->view->assign('next_month', date('Y-m', strtotime('+1 month', $monthData['month_date'])));

        // cast to user timezone
        $this->view->assign('today', waDateTime::date('j', null, $timezone));
        $this->view->assign('today_month', waDateTime::date('n', null, $timezone));

        $this->setTemplate('templates/include/monthcalendar.html');
    }
}
