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

        /** @var pocketlistsAppLinkInterface $app */
        $app = pl2()->getLinkedApp($app_id);

        if (!$app->userCanAccess()) {
            throw new pocketlistsForbiddenException();
        }

        $calendar_html = (new pocketlistsAppMonthAction())->display();

        $this->view->assign(compact('calendar_html', 'app'));
        $this->view->assign('user', $this->user);
    }
}
