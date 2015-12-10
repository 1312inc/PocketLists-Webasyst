<?php

class pocketlistsSettingsAction extends  waViewAction
{
    // todo: const for settngs;
    const APP_ICON_OVERDUE = 2;

    public function execute()
    {
        $this->view->assign('settings', pocketlistsUserSettings::getAllSettings());

        $pm = new pocketlistsPocketModel();
        $this->view->assign('pockets', $pm->getAllPockets(wa()->getUser()));
    }
}