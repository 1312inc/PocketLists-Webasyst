<?php

class pocketlistsLogAction
{
    const LIST_CREATED     = 'list_created';
    const LIST_DELETED     = 'list_deleted';
    const LIST_ARCHIVED    = 'list_archived';
    const LIST_UNARCHIVED  = 'list_unarchived';
    const NEW_ITEMS        = 'new_items';
    const NEW_ITEM         = 'new_item';
    const NEW_SELF_ITEM    = 'new_self_item';
    const ITEM_ASSIGN      = 'item_assign';
    const ITEM_ASSIGN_TEAM = 'item_assign_team';
    const ITEM_COMPLETED   = 'item_completed';
    const ITEM_COMMENT     = 'item_comment';

    /**
     * @var string
     */
    private static $ext;
    /**
     * @var array
     */
    private static $cache = [];

    /**
     * @var string
     */
    private $app_url;

    /**
     * @var bool
     */
    private $logs;

    /**
     * @var array
     */
    private $ext_logs;

    /**
     * @var  pocketlistsWaLogModel
     */
    private $logModel;

    /**
     * @var array
     */
    private $lists;

    /**
     * @var array
     */
    private $pockets;

    /**
     * @var int
     */
    public $user_id;

    /**
     * @var string
     */
    private $use_last_user_activity;

    /**
     * @param bool $datetime
     */
    public function useLastUserActivity($datetime = false)
    {
        $this->use_last_user_activity = $datetime;
    }

    /**
     * pocketlistsLogAction constructor.
     */
    public function __construct()
    {
        $this->app_url = wa(pocketlistsHelper::APP_ID)->getConfig()->getBackendUrl(true).'pocketlists/';
        $this->logModel = new pocketlistsWaLogModel();
        $this->logs = false;

        self::$ext = pocketlistsHelper::APP_ID.'_ext';
    }

    /**
     * @return array
     */
    public static function getActions()
    {
        return [
            self::LIST_CREATED     => [
                'name' => /*_w*/
                    ('created new list'),
            ],
            self::LIST_DELETED     => [
                'name' => /*_w*/
                    ('deleted a list'),
            ],
            self::LIST_ARCHIVED    => [
                'name' => /*_w*/
                    ('archived'),
            ],
            self::LIST_UNARCHIVED  => [
                'name' => /*_w*/
                    ('unarchived'),
            ],
            self::NEW_ITEMS        => [
                'name' => /*_w*/
                    ('added new to-dos to'),
            ],
            self::NEW_ITEM         => [
                'name' => /*_w*/
                    ('added new to-do'),
            ],
            self::NEW_SELF_ITEM    => [
                'name' => /*_w*/
                    ('added a to-do to self'),
            ],
            self::ITEM_COMPLETED   => [
                'name' => /*_w*/
                    ('completed'),
            ],
            self::ITEM_COMMENT     => [
                'name' => /*_w*/
                    ('commented'),
            ],
            self::ITEM_ASSIGN      => [
                'name' => /*_w*/
                    ('assigned a to-do to'),
            ],
            self::ITEM_ASSIGN_TEAM => [
                'name' => /*_w*/
                    ('assigned a to-do to'),
            ],
        ];
    }

    /**
     * @param $logs
     *
     * @return array
     */
    public function explainLogs($logs)
    {
        $this->logs = wa()->getConfig()->explainLogs($logs);

        $this->ext_logs = $this->filter();

        foreach ($this->ext_logs as $id => $log_entry) {
            if (!$log_entry) {
                continue;
            }

            $action = $log_entry['action'];

            if (method_exists($this, $action)) {
                $this->ext_logs[$id]['params_html'] = $this->$action($id);
            } else {
                $this->ext_logs[$id]['params_html'] = 'no action for '.$action;
            }
        }

        return $this->ext_logs;
    }

    /**
     * @param $id
     *
     * @return string
     */
    private function list_created($id)
    {
        return '<b>'.$this->getListUrlHtml($id).'</b>';
    }

    /**
     * @param $id
     *
     * @return string
     */
    private function list_deleted($id)
    {
        return !empty($this->ext_logs[$id]['params']['list_name']) ? htmlspecialchars(
            $this->ext_logs[$id]['params']['list_name']
        ) : '';
    }

    /**
     * @param $id
     *
     * @return string
     */
    private function list_archived($id)
    {
        return $this->getListUrlHtml($id);
    }

    /**
     * @param $id
     *
     * @return string
     */
    private function list_unarchived($id)
    {
        return $this->getListUrlHtml($id);
    }

    /**
     * @param $id
     *
     * @return mixed|string
     */
    private function new_items($id)
    {
        if ($this->ext_logs[$id]['params']['list_id']) {
            return $this->getListUrlHtml($id);
        } else {
            return _w("to his personal to-do stream");
        }
    }

    /**
     * @param $id
     *
     * @return mixed
     * @throws waException
     */
    private function new_self_item($id)
    {
        $f = pl2()->getEntityFactory(pocketlistsItem::class);
        $item = $f->findById($this->ext_logs[$id]['params']['item_id']) ?: $f->createNew();

        return $item->getName();
    }

    /**
     * @param $id
     *
     * @return string
     * @throws waException
     */
    private function item_completed($id)
    {
        $f = pl2()->getEntityFactory(pocketlistsItem::class);
        $item = $f->findById($this->ext_logs[$id]['params']['item_id']) ?: $f->createNew();

        return htmlspecialchars($item->getName());
    }

    /**
     * @param $id
     *
     * @return string
     * @throws waException
     */
    private function item_comment($id)
    {
        $item = false;
        if (!empty($this->ext_logs[$id]['params']['item_id'])) {
            $f = pl2()->getEntityFactory(pocketlistsItem::class);
            /** @var pocketlistsItem $item */
            $item = $f->findById($this->ext_logs[$id]['params']['item_id']) ?: '';
        }

        $html = '';

        if (!empty($this->ext_logs[$id]['pocketlists_ext']['comment']) && $this->ext_logs[$id]['pocketlists_ext']['comment'] instanceof pocketlistsComment) {
            $html .= htmlspecialchars($this->ext_logs[$id]['pocketlists_ext']['comment']->getComment());
        }

        $_link = $this->getListUrlHtml($id, $item ? $item->getName() : '');

        if ($_link)
            $html .= ' @ '.$_link;

        return $html;
    }

    /**
     * @param $id
     *
     * @return string
     * @throws waException
     */
    private function item_assign($id)
    {
        $contact = new waContact($this->ext_logs[$id]['params']['assigned_to']);
        $list_html = $this->getListUrlHtml($id);
        $team_url = '#/team/';
        $teamName = 'no contact';
        if ($contact->exists()) {
            $team_url = $this->app_url.'#/team/'.$contact->get('login').'/';
            $teamName = htmlspecialchars($contact->getName());
        }

        $item = null;
        if (!empty($this->ext_logs[$id]['params']['item_id'])) {
            $item = $this->getItemData($this->ext_logs[$id]['params']['item_id']);
        }

        $_str = '<a href="'.$team_url.'">'.$teamName.'</a>'.($item && $item->getId() ? ' '.htmlspecialchars(
                    $item->getName()
                ) : '');
        if ($list_html) {
            return $_str.' @ '.$list_html;
        } else {
            return $_str;
        }
    }

    /**
     * @param $id
     *
     * @return string
     * @throws waException
     */
    private function new_item($id)
    {
        if ($this->ext_logs[$id]['params']['list_id']) {
            $list_html = $this->getListUrlHtml($id);
        } else {
            $list_html = _w("to his personal to-do stream");
        }

        $item = null;
        if (!empty($this->ext_logs[$id]['params']['item_id'])) {
            $item = $this->getItemData($this->ext_logs[$id]['params']['item_id']);
        }

        return sprintf('%s @ %s', $item && $item->getId() ? ' '.htmlspecialchars($item->getName()) : '', $list_html);
    }

    /**
     * @param $id
     *
     * @return string
     * @throws waException
     */
    private function item_assign_team($id)
    {
        $contact = new waContact($this->ext_logs[$id]['params']['assigned_to']);
        $team_url = '#/team/';
        $teamName = 'no contact';
        if ($contact->exists()) {
            $team_url = $this->app_url.'#/team/'.$contact->get('login').'/';
            $teamName = htmlspecialchars($contact->getName());
        }

        $item = null;
        if (!empty($this->ext_logs[$id]['params']['item_id'])) {
            $item = $this->getItemData($this->ext_logs[$id]['params']['item_id']);
        }

        return sprintf(
            ' <a href="%s">%s</a>%s',
            $team_url,
            $teamName,
            ($item && $item->getId() ? ' '.htmlspecialchars($item->getName()) : '')
        );
    }

    /**
     * @param $id
     *
     * @return string
     */
    private function getListUrlHtml($id, $anchor = '')
    {
        if (!$this->ext_logs[$id][self::$ext]['list']) {
            return '';
        }

        if ($this->ext_logs[$id][self::$ext]['list']->getId()) {
            $list_url = sprintf(
                '%s#/pocket/%s/list/%s/',
                $this->app_url,
                $this->ext_logs[$id][self::$ext]['list']->getPocketId(),
                $this->ext_logs[$id][self::$ext]['list']->getId()
            );
        } else {
            $list_url = $this->app_url;
        }

        $list_name = $anchor
            ? htmlspecialchars($anchor)
            : htmlspecialchars($this->ext_logs[$id][self::$ext]['list']->getName(), ENT_QUOTES);

        return "<a href=\"{$list_url}\">{$list_name}</a>";
    }

    /**
     * @return array|bool
     */
    public function getLogs()
    {
        if (!is_array($this->logs)) {
            $this->logs = $this->logModel->getAllLogs();
        }

        return $this->logs;
    }

    /**
     * @param string $last_activity
     *
     * @return array
     */
    public function getLogsForUser($last_activity = '')
    {
        $this->logs = $this->logModel->getLastLogs($last_activity)->fetchAll();

        $this->ext_logs = $this->filter();

        return $this->ext_logs;
    }

    /**
     * Add extended data and check access level
     *
     * @param bool $user_id
     *
     * @return array
     */
    private function filter($user_id = false)
    {
        $this->user_id = $user_id ? $user_id : wa()->getUser()->getId();
        $this->lists = pocketlistsRBAC::getAccessListForContact($this->user_id);
        $this->pockets = pocketlistsRBAC::getAccessPocketForContact($this->user_id);

        $logs = [];
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

    /**
     * @param $log
     *
     * @return bool
     * @throws waException
     */
    private function canAccess($log)
    {
        /** @var pocketlistsList $list */
        $list = $log[self::$ext]['list'];
        /** @var waContact $assigned */
        $assigned = $log[self::$ext]['assigned'];

        if (in_array(
                $log['action'],
                [
                    self::ITEM_ASSIGN,
                    self::ITEM_ASSIGN_TEAM,
                ]
            ) && !pocketlistsRBAC::canAssign()
            && $assigned && $assigned->getId() != wa()->getUser()->getId()
        ) {
            return false;
        }

        // показывать только админам, иначе пользователи без прав будут видеть в логах названия таких удаленных списков, хотя до этого ничего о них не слышали
        if (in_array($log['action'], [self::LIST_DELETED])) {
            if (pocketlistsRBAC::isAdmin()) {
                return true;
            }

            if ($list instanceof pocketlistsList && in_array($list->getPocketId(), $this->pockets)) {
                return true;
            }

            return false;
        }

        if ($list instanceof pocketlistsList) {
            // в случае "после удаления" показывать записи NEW LIST только админам.
            if (empty($list->getId()) &&
                in_array(
                    $log['action'],
                    [
                        self::LIST_CREATED,
                        self::LIST_DELETED,
                        self::LIST_ARCHIVED,
                        self::LIST_UNARCHIVED,
                        self::NEW_ITEMS,
                        self::NEW_ITEM,
                        self::ITEM_ASSIGN,
                        self::ITEM_COMPLETED,
                        self::ITEM_COMMENT,
                    ]
                ) && !pocketlistsRBAC::isAdmin()
            ) {
                return false;
            }

            // доступ к списку
            if ($list->getId()
                && !pocketlistsRBAC::isAdmin()
                && !in_array($list->getId(), $this->lists)
            ) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $log
     *
     * @return mixed
     * @throws waException
     */
    private function extendLog($log)
    {
        $log['params_html'] = '';

        if ((int)$log['params']) {
        } else {
            $log['params'] = json_decode($log['params'], true);
        }

        $log[self::$ext] = [
            'list'       => [],
            'assigned'   => [],
            'item'       => [],
            'comment'    => [],
            'attachment' => [],
            'is_new'     => false,
        ];

        if ($this->use_last_user_activity && strtotime($this->use_last_user_activity) < strtotime($log['datetime'])) {
            $log[self::$ext]['is_new'] = true;
        }

        if (!empty($log['params']['list_id'])) {
            $log[self::$ext]['list'] = $this->getListData($log['params']['list_id']);
        }

        if (!empty($log['params']['item_id'])) {
            $log[self::$ext]['item'] = $this->getItemData($log['params']['item_id']);
            if ($log[self::$ext]['item'] && !$log[self::$ext]['list']) {
                $log[self::$ext]['list'] = $this->getListData($log[self::$ext]['item']->getListId());
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

    /**
     * @param $id
     *
     * @return mixed
     * @throws waException
     */
    private function getListData($id)
    {
        $id = (int)$id;
        $key = 'list_'.$id;

        if (!isset(self::$cache[$key])) {
            /** @var pocketlistsList $list */
            $list = pl2()->getEntityFactory(pocketlistsList::class)->findById($id);

            if (!$list) {
                $data = (new pocketlistsList())->setName(_w('Pocket Lists'));
            } else {
                $data = $list;
            }
            self::$cache[$key] = $data;
        }

        return self::$cache[$key];
    }

    /**
     * @param $id
     *
     * @return pocketlistsItem
     * @throws waException
     */
    private function getItemData($id)
    {
        $key = 'item_'.$id;

        if (!isset(self::$cache[$key])) {
            $f = pl2()->getEntityFactory(pocketlistsItem::class);
            /** @var pocketlistsItem $item */
            $item = $f->findById($id);

            self::$cache[$key] = $item ?: $f->createNew();
        }

        return self::$cache[$key];
    }

    /**
     * @param $id
     *
     * @return pocketlistsComment|
     * @throws waException
     */
    private function getCommentData($id)
    {
        $key = 'comment_'.$id;

        if (!isset(self::$cache[$key])) {
            $f = pl2()->getEntityFactory(pocketlistsComment::class);
            $comment = $f->findById($id) ?: $f->createNew();

            self::$cache[$key] = $comment;
        }

        return self::$cache[$key];
    }
}
