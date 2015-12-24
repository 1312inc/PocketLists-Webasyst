<?php

class pocketlistsConfig extends waAppConfig
{

    public function onInit()
    {
        $wa = wa();
        $id = $wa->getUser()->getId();
        if ($id && ($wa->getApp() == 'pocketlists') && ($wa->getEnv() == 'backend')) {
            $this->setCount($this->onCount());
        }
    }

    public function onCount()
    {
        $pi = new pocketlistsItemModel();
        return $pi->getAppCountForUser();
    }

    public function checkRights($module, $action)
    {
        return true;
    }

}

