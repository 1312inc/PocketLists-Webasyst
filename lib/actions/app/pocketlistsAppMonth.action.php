<?php

/**
 * Class pocketlistsAppMonthAction
 */
class pocketlistsAppMonthAction extends pocketlistsViewAction
{
    public function execute()
    {
       $app_id = waRequest::get('app');

        if (!$app_id) {
            throw new waException('Not found');
        }

        /** @var pocketlistsItemLinkInterface $app */
        $app = wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedApp($app_id);

        if (!$app->userCanAccess()) {
            throw new waException('Access denied.', 403);
        }

        $timezone = wa()->getUser()->getTimezone();
        $show_month = waRequest::get('month', 0, waRequest::TYPE_INT);

        $im = new pocketlistsItemModel();

        $items = $im->getAppItems($app_id, false, false, $show_month);

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

        $this->view->assign('type', 'app');

        $this->view->assign('app', $app);

        $this->setTemplate('templates/include/monthcalendar.html');
    }
}
