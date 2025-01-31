<?php

/**
 * Class pocketlistsUpgradeAction
 */
class pocketlistsUpgradeAction extends waViewAction
{
    public function execute()
    {
        if (wa()->whichUI() !== '1.3') {
            $this->setLayout(new pocketlistsStaticLayout());
        }
        $this->view->assign('admin', pocketlistsRBAC::isAdmin());
    }
}
