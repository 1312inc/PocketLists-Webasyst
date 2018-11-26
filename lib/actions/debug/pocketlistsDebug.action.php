<?php

/**
 * Class pocketlistsDebugAction
 */
class pocketlistsDebugAction extends waViewAction
{
    public function execute()
    {
        if (!pocketlistsRBAC::isAdmin()) {
            throw new waException('Access denied.', 403);
        }

        $linkedAppsConfig = require_once wa()->getConfig()->getLinkedAppConfigPath();
        if (!is_array($linkedAppsConfig)) {
            $linkedAppsConfig = [];
        }

        $this->view->assign('apps', $linkedAppsConfig);
        $this->view->assign('admin', pocketlistsRBAC::isAdmin());
    }
}
