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
        $logs = parent::explainLogs($logs);
        $app_url = wa()->getConfig()->getBackendUrl(true).$this->getApplication().'/';

        foreach ($logs as $log_id => $log_entry) {
            $logs[$log_id]['params_html'] = '';
            switch ($log_entry['action']) {
                case 'new_items':
                    $list = json_decode($log_entry['params'], true);
                    if ($list['id']) {
                        $list_url = $app_url.'#/pocket/'.$list['pocket_id'].'/list/'.$list['id'].'/';
                        $list_name = htmlspecialchars($list['name'], ENT_QUOTES);
                        $logs[$log_id]['params_html'] .= "<a href=\"{$list_url}\">{$list_name}</a>";
                    } else {
                        $logs[$log_id]['params_html'] .= _w("to his personal to-do stream");
                    }
                    break;
                case 'item_completed':
                    $item = json_decode($log_entry['params'], true);
                    $logs[$log_id]['params_html'] .= htmlspecialchars($item['name']);
                    break;
                case 'list_created':
                    $list = json_decode($log_entry['params'], true);
                    $list_url = $app_url.'#/pocket/'.$list['pocket_id'].'/list/'.$list['id'].'/';
                    $list_name = htmlspecialchars($list['name'], ENT_QUOTES);
                    $logs[$log_id]['params_html'] .= "<a href=\"{$list_url}\">{$list_name}</a>";
                    break;
                case 'list_deleted':
                    break;
                case 'list_archived':
                    $lm = new pocketlistsListModel();
                    $list = $lm->getById($log_entry['params']);
                    $list_url = $app_url.'#/pocket/'.$list['pocket_id'].'/list/'.$list['id'].'/';
                    $list_name = htmlspecialchars($list['name'], ENT_QUOTES);
                    $logs[$log_id]['params_html'] .= "<a href=\"{$list_url}\">{$list_name}</a>";
                    break;
            }
        }

        return $logs;
    }
}
