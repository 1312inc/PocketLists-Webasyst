<?php

class pocketlistsLogAction
{
    const LIST_CREATED = 'list_created';
    const LIST_DELETED = 'list_deleted';
    const LIST_ARCHIVED = 'list_archived';
    const LIST_UNARCHIVED = 'list_unarchived';
    const NEW_ITEMS = 'new_items';
    const NEW_SELF_ITEM = 'new_self_item';
    const ITEM_ASSIGN = 'item_assign';
    const ITEM_ASSIGN_TEAM = 'item_assign_team';
    const ITEM_COMPLETED = 'item_completed';
    const ITEM_COMMENT = 'item_comment';

    private static $ext;
    private static $cache = array();

    private $app_url;
    private $logs;
    private $ext_logs;
    /** @var  pocketlistsWaLogModel */
    private $logModel;
    private $lists;

    /** @var pocketlistsListModel */
    private $lm;
    /** @var pocketlistsItemModel */
    private $im;
    /** @var pocketlistsCommentModel */
    private $cm;

    public $user_id;
    private $use_last_user_activity;

    public function useLastUserActivity($datetime = false)
    {
        $this->use_last_user_activity = $datetime;
    }

    public function __construct()
    {
        $this->app_url = wa()->getConfig()->getBackendUrl(true) . 'pocketlists/';
        $this->logModel = new pocketlistsWaLogModel();
        $this->logs = false;
        $this->lm = new pocketlistsListModel();
        $this->im = new pocketlistsItemModel();
        $this->cm = new pocketlistsCommentModel();

        self::$ext = pocketlistsHelper::APP_ID . '_ext';
    }

    public static function getActions()
    {
        return array(
            self::LIST_CREATED     => array(
                'name' => /*_w*/
                    ('created new list'),
            ),
            self::LIST_DELETED     => array(
                'name' => /*_w*/
                    ('deleted a list'),
            ),
            self::LIST_ARCHIVED    => array(
                'name' => /*_w*/
                    ('archived'),
            ),
            self::LIST_UNARCHIVED  => array(
                'name' => /*_w*/
                    ('unarchived'),
            ),
            self::NEW_ITEMS        => array(
                'name' => /*_w*/
                    ('added new to-dos to'),
            ),
            self::NEW_SELF_ITEM    => array(
                'name' => /*_w*/
                    ('added a to-do to self'),
            ),
            self::ITEM_COMPLETED   => array(
                'name' => /*_w*/
                    ('completed'),
            ),
            self::ITEM_COMMENT     => array(
                'name' => /*_w*/
                    ('commented on a to-do in'),
            ),
            self::ITEM_ASSIGN      => array(
                'name' => /*_w*/
                    ('assigned a to-do to'),
            ),
            self::ITEM_ASSIGN_TEAM => array(
                'name' => /*_w*/
                    ('sent a new to-do'),
            ),
        );
    }

    public function explainLogs($logs)
    {
        $this->logs = wa()->getConfig()->explainLogs($logs);

        $this->ext_logs = $this->filter();

        foreach ($this->ext_logs as $id => $log_entry) {
            if (!$log_entry) {
                continue;
            }
            $action = $log_entry['action'];
            try {
                $this->ext_logs[$id]['params_html'] = $this->$action($id);
            } catch (waException $ex) {
                $this->ext_logs[$id]['params_html'] = 'no action for ' . $action;
            }
        }
        return $this->ext_logs;
    }

    private function list_created($id)
    {
        return self::getListUrlHtml($id);
    }

    private function list_deleted($id)
    {
        return !empty($this->ext_logs[$id]['params']['list_name']) ? htmlspecialchars($this->ext_logs[$id]['params']['list_name']) : '';
    }

    private function list_archived($id)
    {
        return self::getListUrlHtml($id);
    }

    private function list_unarchived($id)
    {
        return self::getListUrlHtml($id);
    }

    private function new_items($id)
    {
        if ($this->ext_logs[$id]['params']['list_id']) {
            return self::getListUrlHtml($id);
        } else {
            return _w("to his personal to-do stream");
        }
    }

    private function new_self_item($id)
    {
        $item = $this->getItemData($this->ext_logs[$id]['params']['item_id']);
        return $item['name'];
    }

    private function item_completed($id)
    {
        $im = new pocketlistsItemModel();
        $item = $im->getById($this->ext_logs[$id]['params']['item_id']);
        return htmlspecialchars($item['name_original']);
    }

    private function item_comment($id)
    {
        return self::getListUrlHtml($id);
    }

    private function item_assign($id)
    {
        $contact = new waContact($this->ext_logs[$id]['params']['assigned_to']);
        return $contact->getName() . ": " . self::getListUrlHtml($id);
    }

    private function item_assign_team($id)
    {
        $contact = new waContact($this->ext_logs[$id]['params']['assigned_to']);
        $team_url = $this->app_url . '#/team/' . $contact->get('login') . '/';
        $item = $this->getItemData($this->ext_logs[$id]['params']['item_id']);
        return htmlspecialchars($item['name_original']) . " " . _w("to") . " <a href=\"{$team_url}\">" . $contact->getName() . "</a>";
    }

    private function getListUrlHtml($id)
    {
        if (!$this->ext_logs[$id][self::$ext]['list']) {
            return "";
        }
        $list_url = $this->app_url . '#/list/' . $this->ext_logs[$id][self::$ext]['list']['id'] . '/';
        $list_name = htmlspecialchars($this->ext_logs[$id][self::$ext]['list']['name'], ENT_QUOTES);
        return "<a href=\"{$list_url}\">{$list_name}</a>";
    }

    public function getLogs()
    {
        if (!is_array($this->logs)) {
            $this->logs = $this->logModel->getAllLogs();
        }
        return $this->logs;
    }

    public function getLogsForUser($last_activity = '')
    {
        $this->logs = $this->logModel->getLastLogs($last_activity)->fetchAll();

        $this->ext_logs = $this->filter();

        return $this->ext_logs;
    }

    /**
     * Add extended data and check access level
     * @param bool $user_id
     * @return array
     */
    private function filter($user_id = false)
    {
        $this->user_id = $user_id ? $user_id : wa()->getUser()->getId();
        $this->lists = pocketlistsRBAC::getAccessListForContact($this->user_id);

        $logs = array();
        foreach ($this->getLogs() as $id => $log) {
            // will skip other logs
            if ($log['app_id'] != pocketlistsHelper::APP_ID) {
                continue;
            }

            $logs[$id] = $this->extendLog($log);
            if (!$this->canAccess($logs[$id])) {
                $logs[$id] = false;
            }
        }
        return $logs;
    }

    private function canAccess($log)
    {
        $list = $log[self::$ext]['list'];

        if (in_array($log['action'], array(
                self::ITEM_ASSIGN,
                self::ITEM_ASSIGN_TEAM,
                self::NEW_SELF_ITEM,
            )) && !pocketlistsRBAC::canAssign()
        ) {
            return false;
        }

        if (pocketlistsRBAC::isAdmin() || ($list && $this->lists && in_array($list['id'], $this->lists))) {
            return true;
        }

        return false;
    }

    private function extendLog($log)
    {
        $log['params_html'] = '';

        if (intval($log['params'])) {
        } else {
            $log['params'] = json_decode($log['params'], true);
        }

        $log[self::$ext] = array(
            'list'       => array(),
            'assigned'   => array(),
            'item'       => array(),
            'comment'    => array(),
            'attachment' => array(),
            'is_new'     => false,
        );

        if ($this->use_last_user_activity && strtotime($this->use_last_user_activity) < strtotime($log['datetime'])) {
            $log[self::$ext]['is_new'] = true;
        }

        if (!empty($log['params']['list_id'])) {
            $log[self::$ext]['list'] = $this->getListData($log['params']['list_id']);
        }

        if (!empty($log['params']['item_id'])) {
            $log[self::$ext]['item'] = $this->getItemData($log['params']['item_id']);
            if ($log[self::$ext]['item'] && !$log[self::$ext]['list']) {
                $log[self::$ext]['list'] = $this->getListData($log[self::$ext]['item']['list_id']);
            }
        }

        if (!empty($log['params']['assigned_to'])) {
            $log[self::$ext]['assigned'] = new waContact($log['params']['assigned_to']);
        }

        if (!empty($log['params']['comment_id'])) {
            $log[self::$ext]['comment'] = $this->getCommentData($log['params']['comment_id']);
        }

        return $log;
    }

    private function getListData($id)
    {
        if (!isset(self::$cache['list_' . $id])) {
            self::$cache['list_' . $id] = $this->lm->getById($id);
        }
        return self::$cache['list_' . $id];
    }

    private function getItemData($id)
    {
        if (!isset(self::$cache['item_' . $id])) {
            $item = $this->im->getById($id);
            self::$cache['item_' . $id] = $this->im->extendItemData($item);;
        }
        return self::$cache['item_' . $id];
    }

    private function getCommentData($id)
    {
        if (!isset(self::$cache['comment_' . $id])) {
            self::$cache['comment_' . $id] = $this->lm->getById($id);
        }
        return self::$cache['comment_' . $id];
    }

}
