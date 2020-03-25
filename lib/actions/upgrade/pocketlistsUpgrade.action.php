<?php

/**
 * Class pocketlistsUpgradeAction
 */
class pocketlistsUpgradeAction extends waViewAction
{
    public function execute()
    {
        $this->view->assign('admin', pocketlistsRBAC::isAdmin());
    }
}
