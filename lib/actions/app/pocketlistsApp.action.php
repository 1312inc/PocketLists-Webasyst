<?php

/**
 * Class pocketlistsAppAction
 */
class pocketlistsAppAction extends waViewAction
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $app_id = waRequest::get('app');

        $calendar_html = wao(new pocketlistsAppMonthAction())->display();
        $this->view->assign('calendar_html', $calendar_html);

        $this->view->assign('app', wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedApp($app_id));
    }
}
