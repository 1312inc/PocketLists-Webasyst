<?php

class pocketlistsActivityAction extends waViewAction
{
    public function execute()
    {
        $last_activity = pocketlistsActivity::getUserActivity();
        $pactions = new pocketlistsLogAction();
        $pactions->useLastUserActivity($last_activity);
        $this->view->assign('activities', $pactions->getLogsForUser());
    }
}
