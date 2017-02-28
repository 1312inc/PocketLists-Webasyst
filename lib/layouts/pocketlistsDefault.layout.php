<?php

class pocketlistsDefaultLayout extends waLayout
{
    public function execute()
    {
        $us = new pocketlistsUserSettings();
        if ($us->appIcon() === false) {
            $us->saveDefaults();
        }
        $this->executeAction('sidebar', new pocketlistsBackendSidebarAction());
        $this->view->assign('isAdmin', wa()->getUser()->isAdmin() || wa()->getUser()->isAdmin('pocketlists') ? 1 : 0);

//        pocketlistsActivity::setUserActivity();
    }
}
