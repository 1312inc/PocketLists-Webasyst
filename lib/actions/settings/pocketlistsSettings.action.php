<?php

class pocketlistsSettingsAction extends  waViewAction
{
    public function execute()
    {
        $us = new pocketlistsUserSettings(wa()->getUser()->getId());
        $this->view->assign('settings', $us->getAllSettings());

        $pm = new pocketlistsPocketModel();
        $this->view->assign('pockets', $pm->getAllPockets(wa()->getUser()));
    }
}