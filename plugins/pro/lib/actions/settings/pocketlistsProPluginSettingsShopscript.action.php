<?php


class pocketlistsProPluginSettingsShopscriptAction extends pocketlistsProPluginAbstractViewAction
{
    public function runAction($params = null)
    {
        $this->view->assign('params', 'shopscript');
    }
}
