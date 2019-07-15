<?php


class pocketlistsProPluginSettingsStatusesAction extends pocketlistsProPluginAbstractViewAction
{
    public function runAction($params = null)
    {
        $this->view->assign('params', 'statuses');
    }
}
