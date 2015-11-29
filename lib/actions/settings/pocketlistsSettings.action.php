<?php

class pocketlistsSettingsAction extends  waViewAction
{
    const APP_ICON_OVERDUE = 2;

    public function execute()
    {
        $this->view->assign('settings', pocketlistsHelper::getUserSettings());

        $pm = new pocketlistsPocketModel();
        $this->view->assign('pockets', $pm->getAllPockets(wa()->getUser()));
    }
}