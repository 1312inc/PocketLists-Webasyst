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

    public function explainLogs($logs)
    {
        $logs = pocketlistsLogAction::explainLogs($logs);
        return $logs;
    }

    public function getCronJob($name = null)
    {
        static $tasks;
        if (!isset($tasks)) {
            $tasks = array();
            $path = $this->getAppConfigPath('cron');
            if (file_exists($path)) {
                $tasks = include($path);
            } else {
                $tasks = array();
            }
        }
        return $name ? (isset($tasks[$name]) ? $tasks[$name] : null) : $tasks;
    }
}
