<?php


class pocketlistsProPluginSettingsTemplatesAction extends pocketlistsProPluginAbstractViewAction
{
    public function runAction($params = null)
    {
        $this->view->assign('params', 'templates');
    }
}
