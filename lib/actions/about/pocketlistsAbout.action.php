<?php

/**
 * Class pocketlistsAboutAction
 */
class pocketlistsAboutAction extends pocketlistsViewAction
{
    public function runAction($params = null)
    {
        if (wa()->whichUI() === '1.3') {
            if (!waRequest::isXMLHttpRequest()) {
                $this->redirect(wa()->getAppUrl(null, true));
            }
        } else {
            $this->setLayout(new pocketlistsStaticLayout());
        }
    }
}
