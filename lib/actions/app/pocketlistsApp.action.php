<?php

/**
 * Class pocketlistsAppAction
 */
class pocketlistsAppAction extends pocketlistsViewAction
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $app_id = waRequest::get('app');

        /** @var pocketlistsItemLinkInterface $app */
        $app = wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedApp($app_id);

        if (!$app->userCanAccess()) {
            throw new waException('Access denied.', 403);
        }

        $calendar_html = wao(new pocketlistsAppMonthAction())->display();
        $this->view->assign(compact('calendar_html', 'app'));
        $this->view->assign('user', $this->user);
    }
}
