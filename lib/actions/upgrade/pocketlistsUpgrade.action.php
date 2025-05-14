<?php

/**
 * Class pocketlistsUpgradeAction
 */
class pocketlistsUpgradeAction extends waViewAction
{
    public function execute()
    {
        if (wa()->whichUI() === '1.3') {
            if (!waRequest::isXMLHttpRequest()) {
                $this->redirect(wa()->getAppUrl());
            }
        } else {
            $this->setLayout(new pocketlistsStaticLayout());
        }
        $this->view->assign('admin', pocketlistsRBAC::isAdmin());
    }
}
