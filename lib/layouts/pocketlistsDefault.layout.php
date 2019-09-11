<?php

/**
 * Class pocketlistsDefaultLayout
 */
class pocketlistsDefaultLayout extends waLayout
{
    /**
     * @throws waException
     */
    public function execute()
    {
        $us = new pocketlistsUserSettings();
        if ($us->appIcon() === false) {
            $us->saveDefaults();
        }
        $this->executeAction('sidebar', new pocketlistsBackendSidebarAction());
        $this->view->assign('isAdmin', (int)pocketlistsRBAC::isAdmin());

        $this->view->assign(
            pocketlistsEventStorage::WA_BACKEND_HEAD,
            wa()->event(pocketlistsEventStorage::WA_BACKEND_HEAD)
        );
    }
}
