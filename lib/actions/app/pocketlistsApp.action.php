<?php

/**
 * Class pocketlistsAppAction
 */
class pocketlistsAppAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws pocketlistsForbiddenException
     * @throws waException
     */
    public function runAction($params = null)
    {
        $app_id = waRequest::get('app');

        /** @var pocketlistsAppLinkInterface $app */
        $app = pl2()->getLinkedApp($app_id);

        if (!$app->userCanAccess(null, 'sidebar')) {
            throw new pocketlistsForbiddenException();
        }

        $calendar_html = (new pocketlistsAppMonthAction())->display(false);

        $this->view->assign(compact('calendar_html', 'app'));
        $this->view->assign('user', $this->user);
    }
}
