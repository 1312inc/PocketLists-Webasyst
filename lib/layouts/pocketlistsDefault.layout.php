<?php

class pocketlistsDefaultLayout extends waLayout
{
    public function execute()
    {
        $us = new pocketlistsUserSettings();
        if ($us->appIcon() === false) {
            $us->saveDefaults();
        }
        $this->view->assign('sidebar_todo_count', wa('pocketlists')->getConfig()->onCount());
    }
}