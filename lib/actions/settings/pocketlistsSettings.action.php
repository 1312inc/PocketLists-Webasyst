<?php

class pocketlistsSettingsAction extends  waViewAction
{
    public function execute()
    {
        $us = new pocketlistsUserSettings(wa()->getUser()->getId());
        $settings = $us->getAllSettings();
        $this->view->assign('settings', $settings);

        $pm = new pocketlistsPocketModel();
        $this->view->assign('pockets', $pm->getAllPockets(wa()->getUser()->getId()));

        if ($inbox_list_id = $us->getStreamInboxList()) {
            $lm = new pocketlistsListModel();
            $inbox_list = $lm->getById($inbox_list_id);
            $this->view->assign('inbox_lists', $lm->getLists($inbox_list['pocket_id']));
            $this->view->assign('inbox_list', $inbox_list);
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