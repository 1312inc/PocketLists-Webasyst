<?php

class pocketlistsSettingsAction extends  waViewAction
{
    public function execute()
    {
        $us = new pocketlistsUserSettings(wa()->getUser()->getId());
        $this->view->assign('settings', $us->getAllSettings());

        if (pocketlistsHelper::isAdmin()) {
            $pm = new pocketlistsPocketModel();
            $this->view->assign('pockets', $pm->getAllPockets(wa()->getUser()->getId()));
        }

        $asp = new waAppSettingsModel();
        $this->view->assign(
            'last_recap_cron_time',
            $asp->get(wa()->getApp(), 'last_recap_cron_time')
        );
        $this->view->assign('cron_command', 'php '.wa()->getConfig()->getRootPath().'/cli.php '.wa()->getApp().' recap');
        $this->view->assign('admin', pocketlistsHelper::isAdmin());
    }
}