<?php

class pocketlistsSettingsAction extends  waViewAction
{
    const APP_ICON_OVERDUE = 2;

    public function execute()
    {
        $cs = new waContactSettingsModel();
        $app_name = wa()->getApp();
//        $settings = $sm->get($app_name);
        $settings = $cs->get(wa()->getUser()->getId(), $app_name);

        $this->view->assign('settings', pocketlistsHelper::getUserSettings());

        $pm = new pocketlistsPocketModel();
        $this->view->assign('pockets', $pm->getAll());
    }
}