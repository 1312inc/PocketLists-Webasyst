<?php

class pocketlistsSettingsAction extends  waViewAction
{
    const APP_ICON_OVERDUE = 2;

    public function execute()
    {
        $this->view->assign('settings', pocketlistsHelper::getUserSettings());

        $pm = new pocketlistsPocketModel();
        $rights = false;
        if (!wa()->getUser()->isAdmin()) {
            $rights = $this->getRights();
        }
        $this->view->assign('pockets', $pm->getAllPockets($rights));
    }
}