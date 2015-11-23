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

        $this->view->assign('app_icon', $settings['app_icon']);
        $this->view->assign('email_me', json_decode($settings['email_me'], true));
        $this->view->assign('daily_recap', json_decode($settings['daily_recap'], true));

        $pm = new pocketlistsPocketModel();
        $this->view->assign('pockets', $pm->getAll());
    }
}