<?php

/**
 * Class pocketlistsItemModel
 *
 * @property int    $list_id
 * @property int    $contact_id
 * @property int    $parent_id
 * @property int    $sort
 * @property int    $has_children
 * @property int    $status
 * @property int    $priority
 * @property int    $calc_priority
 * @property string $create_datetime
 * @property string $update_datetime
 * @property string $complete_datetime
 * @property int    $complete_contact_id
 * @property string $name
 * @property string $note
 * @property string $due_date
 * @property string $due_datetime
 * @property int    $location_id
 * @property float  $amount
 * @property string $currency_iso3
 * @property int    $assigned_contact_id
 * @property int    $repeat
 * @property int    $key_list_id
 * @property array  $chat
 * @property array  $attachments
 * @property string $age_time
 */
class pocketlistsItemModel extends kmModelExt
{
    const PRIORITY_NORM       = 0;
    const PRIORITY_GREEN      = 1;
    const PRIORITY_YELLOW     = 2;
    const PRIORITY_RED        = 3;
    const PRIORITY_BLACK      = 4;
    const PRIORITY_BURNINHELL = 5;

    protected $table = 'pocketlists_item';

    /**
     * @var pocketlistsItemLinkModel[]|[]
     */
    protected $linkedEntities;

    public $chat = [
        'current_user' => [],
        'comments'     => [],
    ];

    public $childs = [];

    public function init()
    {
        $this->chat = [
            'current_user' => [
                'username' => wa()->getUser()->getName(),
                'userpic'  => wa()->getUser()->getPhoto(20),
            ],
            'comments'     => [],
        ];
    }

    public static function getPriorities()
    {
        return [
            'norm'       => self::PRIORITY_NORM,
            'green'      => self::PRIORITY_GREEN,
            'yellow'     => self::PRIORITY_YELLOW,
            'red'        => self::PRIORITY_RED,
            'black'      => self::PRIORITY_BLACK,
            'burninhell' => self::PRIORITY_BURNINHELL,
        ];
    }

    /**
     * @param bool $contact_id
     * @param bool $date_range
     *
     * @return array
     */
    public function getCompletedItems($contact_id = false, $date_range = false)
    {
        return $this->getLogbookItems($contact_id, $date_range);
    }

//    public function getDefaultAttributes()
//    {
//        return array_merge(
//            parent::getDefaultAttributes(),
//            [
//                'chat'        => [
//                    'current_user' => [
//                        'username' => wa()->getUser()->getName(),
//                        'userpic'  => wa()->getUser()->getPhoto(20),
//                    ],
//                    'comments'     => [],
//                ],
//                'attachments' => [],
//            ]
//        );
//    }

    /**
     * @param bool $contact_id
     * @param bool $date_range
     * @param bool $completed
     * @param int  $start
     * @param int  $limit
     *
     * @return array
     */
    public function getLogbookItems(
        $contact_id = false,
        $date_range = false,
        $completed = false,
        $start = 0,
        $limit = 50
    ) {
        $by_user = '';
        if ($contact_id) {
            $by_user = 'AND i.complete_contact_id = i:contact_id';
        }
        $by_date_range = '';
        if ($date_range && is_array($date_range)) {
            if (!empty($date_range['after'])) {
                $by_date_range = '  AND i.complete_datetime > s:date_after';
            }
            if (!empty($date_range['before'])) {
                $by_date_range .= '  AND i.complete_datetime < s:date_before';
            }
        }

        $only_completed = '';
        if ($completed) {
            $only_completed = " AND i.status > 0";
        }

        $lists = pocketlistsRBAC::getAccessListForContact();
        $list_sql = $lists ? "AND (l.id IN (i:list_ids) OR (l.id IS NULL AND i.contact_id = i:contact_id))" : "AND (l.id IS NULL AND i.contact_id = i:contact_id)";
        $sql = $this->getQuery().
                "LEFT JOIN (select i2.name, l2.*
                          from pocketlists_list l2
                                 JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id
                WHERE
                  i.key_list_id IS NULL
                  {$list_sql}
                  {$only_completed}
                  {$by_user}
                  {$by_date_range}
                ORDER BY i.complete_datetime DESC
                LIMIT {$start}, {$limit}";

        $items = $this->query(
            $sql,
            [
                'contact_id'  => wa()->getUser()->getId(),
                'list_ids'    => $lists,
                'date_after'  => !empty($date_range['after']) ? $date_range['after'] : '',
                'date_before' => !empty($date_range['before']) ? $date_range['before'] : '',
                'start'       => $start,
                'limit'       => $limit,
            ]
        )->fetchAll();

//        $activities = $this->getLastActivities();
//        $result = [];
//        foreach ($activities as $id => $item) {
//            if (isset($items[$id])) {
//                $result[$id] = $this->extendItemData($items[$id]);
//                unset($items[$id]);
//            }
//        }

//        $items = self::generateModels($items);
//
//        if ($items) {
//            foreach ($items as $id => $item) {
//                $result[$id] = $this->extendItemData($items[$id]);
//            }
//        }

//        return $result;
//        return $this->getTree($items, $tree);
        return $items;
    }

    /**
     * @param bool $contact_id
     * @param bool $date
     *
     * @return array
     */
    public function getToDo($contact_id = false, $date = false)
    {
        $date = $date ?: false;

        if (!$contact_id) {
            $contact_id = wa()->getUser()->getId();
        }
//
//        $key = $this->getCacheKey($contact_id.$date);
//
//        return $this->getFromCache(
//            $key,
//            function () use ($key, $contact_id, $date) {
//                $cache = wa()->getCache();
//                $result = false;
//
//                if ($cache) {
//                    $result = $cache->get($key);
//                }

//                if (!$result) {
                    $items = $this->fetchTodo($contact_id, $date, $calc_priority = []);

//                    $items = self::generateModels($items);

                    $result = [
                        0 => [],
                        1 => [],
                    ];

//                    if ($items) {
//                        foreach ($items as $id => $item) {
//                            $result[$item['status']][$id] = $this->extendItemData($item);
//                        }
//
//                        $result = [
//                            0 => $this->getProperSort($result[0]),
//                            1 => $result[1],
//                        ];
//                    }
//
//                    if ($cache) {
//                        $cache->set($key, $result, self::TTL);
//                    }
//                }
//
                return $items;
//                return $result;
//            }
//        );

//        return $this->getTree($items, true);
    }

    /**
     * @param $contact_id
     * @param $date
     *
     * @return array
     *
     * @throws waDbException
     * @throws waException
     */
    public function fetchTodo($contact_id, $date, array $calc_priority = [])
    {
        // get to-do items only from accessed pockets
//        $lists = pocketlistsHelper::getAccessListForContact($contact_id);

        $select_sql = [
            "i.*",
            "IF(uf.contact_id, 1, 0) favorite",
        ];
        $join_sql = [
            "",
            "pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id",
            "(select i2.name, l2.*
                  from pocketlists_list l2
                         JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id",
        ];
        $and_sql = [
            "(".pocketlistsRBAC::filterListAccess($lists, $contact_id)." OR l.id IS NULL)",
            // get to-do items only from accмфп essed pockets
            "l.archived = 0",
        ];
        $or_sql = [
            "(i.list_id IS NULL AND i.key_list_id IS NULL AND i.contact_id = i:contact_id) /* My to-dos to self */",
            "(i.key_list_id IS NULL AND i.assigned_contact_id = i:contact_id) /* To-dos assigned to me by other users */",
        ];

        if ($date) {
            $and_sql[] = "((i.status = 0 AND (i.due_date = s:date OR DATE(i.due_datetime) = s:date)) OR (i.status > 0 AND DATE(i.complete_datetime) = s:date)) /* with due date or completed this day */";
        }

        $us = new pocketlistsUserSettings($contact_id);
        switch ($us->myToDosCreatedByMe()) {
            case pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_ME_IN_SHARED_ANY_LIST:
                $or_sql[] = "(i.list_id IS NOT NULL AND i.key_list_id IS NULL AND i.contact_id = i:contact_id) /* To-dos created by me in shared lists in just any list */";
                break;
            case pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_ME_IN_SHARED_FAVORITE_LISTS:
                $or_sql[] = "(i.list_id IS NOT NULL AND i.key_list_id IS NULL AND i.contact_id = i:contact_id AND uf2.contact_id = i:contact_id) /* To-dos created BY other users IN shared lists only in lists which I marked as favorite*/";
                break;
        }

        switch ($us->myToDosCreatedByOthers()) {
            case pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_OTHER_IN_SHARED_LISTS_FAVORITE_LISTS:
                $or_sql[] = "(i.list_id IS NOT NULL AND i.key_list_id IS NULL AND i.contact_id <> i:contact_id AND uf2.contact_id = i:contact_id) /* To-dos created BY other users IN shared lists only in lists which I marked as favorite*/";
                break;
            case pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_OTHER_IN_SHARED_LISTS_GREEN_YELLOW_RED_ALL_LISTS:
                $tomorrow = date("Y-m-d", strtotime("+1 day"));
                $day_after_tomorrow = date("Y-m-d", strtotime("+2 day"));
                $or_sql[] = "(i.list_id IS NOT NULL AND i.key_list_id IS NULL AND i.contact_id <> i:contact_id AND (i.due_date <= '".$tomorrow."' OR i.due_datetime < '".$day_after_tomorrow."' OR i.priority IN (".implode(
                        ',',
                        [
                            self::PRIORITY_GREEN,
                            self::PRIORITY_YELLOW,
                            self::PRIORITY_RED,
                            self::PRIORITY_BLACK,
                            self::PRIORITY_BURNINHELL,
                        ]
                    )."))) /* To-dos created BY other users IN shared lists all Green, Yellow, and Red to-dos from all lists*/";
                break;
        }

        if ($us->myToDosCreatedByOthers() == pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_OTHER_IN_SHARED_LISTS_FAVORITE_LISTS
            || $us->myToDosCreatedByMe() == pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_ME_IN_SHARED_FAVORITE_LISTS) {
            $join_sql[] = "pocketlists_user_favorites uf2 ON uf2.contact_id = i:contact_id AND uf2.item_id = i2.id";
            $select_sql[] = "IF(uf2.contact_id, 1, 0) favorite_list";
        }

        $and_sql[] = "(i.assigned_contact_id = i:contact_id OR i.assigned_contact_id IS NULL)";

        if ($calc_priority) {
            $and_sql[] = '(i.calc_priority in (i:calc_priority))';
        }

        $or_sql = implode("\n OR ", $or_sql);
        $and_sql = implode("\n AND ", $and_sql);
        $join_sql = implode("\n LEFT JOIN ", $join_sql);
        $select_sql = implode(",\n", $select_sql);

        $sql = "SELECT
                  {$select_sql}
                FROM {$this->table} i
                {$join_sql}
                WHERE
                  ({$or_sql}) 
                  AND 
                  {$and_sql}
                GROUP BY i.id
                ORDER BY
                  i.status,
                  (i.complete_datetime IS NULL), i.complete_datetime DESC";

        $items = $this->query(
            $sql,
            [
                'list_ids'      => $lists,
                'contact_id'    => $contact_id,
                'date'          => $date,
                'calc_priority' => $calc_priority,
            ]
        )->fetchAll();

        return $items;
    }

    /**
     * @param bool $contact_id
     *
     * @return array
     */
    public function getFavoritesCount($contact_id = false)
    {
        if (!$contact_id) {
            $contact_id = wa()->getUser()->getId();
        }

        $lists = pocketlistsRBAC::getAccessListForContact($contact_id);
        $lists_sql = $lists ? " AND (l.id IN (i:list_ids) OR l.id IS NULL) /* ONLY items from accessed pockets or NULL-list items */" : " AND l.id IS NULL /* ONLY items from accessed pockets or NULL-list items */";
        $sql = "SELECT
                  SUM(i.status > 0) done,
                  SUM(i.status = 0) undone
                FROM {$this->table} i
                LEFT JOIN pocketlists_list l ON l.id = i.key_list_id
                LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id
                WHERE
                  uf.item_id IS NOT NULL
                  AND l.archived = 0 /* ONLY not archived items */
                  {$lists_sql}";

        return $this->query(
            $sql,
            [
                'list_ids'   => $lists,
                'contact_id' => $contact_id,
            ]
        )->fetch();
    }

    /**
     * @param bool $contact_id
     * @param bool $date
     *
     * @return array
     */
    public function getFavorites($contact_id = false, $date = false)
    {
        if (!$contact_id) {
            $contact_id = wa()->getUser()->getId();
        }
        // get to-do items only from accessed pockets
        $lists = pocketlistsRBAC::getAccessListForContact($contact_id);
        $lists_sql = $lists ? " AND (l.id IN (i:list_ids) OR l.id IS NULL) /* ONLY items from accessed pockets or NULL-list items */" : " AND l.id IS NULL /* ONLY items from NULL-list items */";

        $due_date = $date
            ?
            "AND ((i.status = 0 AND (i.due_date = s:date OR DATE(i.due_datetime) = s:date)) OR (i.status > 0 AND DATE(i.complete_datetime) = s:date)) /* with due date or completed this day */"
            :
            "";
        $sql = "SELECT
                  i.id id,
                  i.parent_id parent_id,
                  i.has_children has_children,
                  i.name name,
                  i.note note,
                  i.status status,
                  i.priority priority,
                  i.contact_id contact_id,
                  i.create_datetime create_datetime,
                  i.due_date due_date,
                  i.due_datetime due_datetime,
                  i.complete_datetime complete_datetime,
                  i.complete_contact_id complete_contact_id,
                  i.assigned_contact_id assigned_contact_id,
                  i.key_list_id key_list_id,
                  l.id list_id,
                  l.icon list_icon,
                  l.color list_color,
                  l.name list_name,
                  IF(uf.contact_id, 1, 0) favorite
                FROM {$this->table} i
                LEFT JOIN (select i2.name, l2.*
                          from pocketlists_list l2
                                 JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id
                LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id
                WHERE
                uf.item_id IS NOT NULL
                AND l.archived = 0 /* ONLY not archived items */
                {$due_date}
                {$lists_sql}
                ORDER BY
                  i.status,
                  (i.complete_datetime IS NULL), i.complete_datetime DESC";

        $items = $this->query(
            $sql,
            [
                'list_ids'   => $lists,
                'date'       => $date,
                'contact_id' => $contact_id,
            ]
        )->fetchAll();

//        $result = [
//            0 => [],
//            1 => [],
//        ];

//        $items = self::generateModels($items);

//        if ($items) {
//            foreach ($items as $id => $item) {
//                $result[$item['status']][$id] = $this->extendItemData($item);
//            }
//        }

//        return [
//            0 => $this->getProperSort($result[0]),
//            1 => $result[1],
//        ];
//        return $this->getTree($items, true);

        return $items;
    }

    /**
     * @param array|int $ids
     * @param bool      $user_id
     *
     * @return array|mixed|null
     */
   /* public function getById($ids, $user_id = false)
    {
        $key = $this->getCacheKey(serialize($ids).$user_id);

        return $this->getFromCache(
            $key,
            function () use ($key, $ids, $user_id) {
                if (!$user_id) {
                    $user_id = wa()->getUser()->getId();
                }
                if (!is_array($ids)) {
                    $ids = [$ids];
                }
//        $items = parent::getById($id);
                $items = $this->query(
                    $this->getQuery()."WHERE i.id IN (i:id)",
                    ['contact_id' => $user_id, 'id' => $ids]
                )->fetchAll();
//        $items = $this->getItems($this->getQuery(), null, false);
//        return $items;

                self::$cached[$key] = pocketlistsItemModel::generateModels($items, count($ids) == 1);

                return self::$cached[$key];
            }
        );
    }*/

    /**
     * @param      $id
     * @param      $item
     * @param bool $silent
     *
     * @return bool
     */
    public function addCalculatedPriorityDataAndSave($id, $item, $silent = false)
    {
        $email_to_assigned_contact = false;
        $old_item = ['assigned_contact_id' => false];
        if (!$silent && $item['assigned_contact_id']) {
            $us = new pocketlistsUserSettings($item['assigned_contact_id']);
            $email_to_assigned_contact = $us->emailWhenNewAssignToMe();
            if ($email_to_assigned_contact) {
                $old_item = $this->getById($id);
            }
        }

        $this->addPriorityData($item);
        $saved = $id ? $this->updateById($id, $item) : $this->insert($item);
        if ($saved) {
            if (!$silent && $email_to_assigned_contact && // settings are set
                $item['assigned_contact_id'] != wa()->getUser()->getId() && // do not email if I assign myself
                $item['assigned_contact_id'] != $old_item['assigned_contact_id']
            ) { // assigned id is updated
                throw new waException('OLOLOL');
//                pocketlistsNotifications::notifyAboutNewAssign($this->prepareOutput($item), wa()->getUser()->getName());
            }

            return true;
        }

        return false;
    }

    /**
     * @return string
     */
    private function getQuery()
    {
        return "SELECT
                  i.*,
                  IF(uf.contact_id, 1, 0) favorite,
                  /*pl.name list_name,
                  pl.sort list_sort,
                  pl.type list_type,
                  pl.icon list_icon,
                  pl.archived list_archived,
                  pl.hash list_hash,
                  pl.color list_color,*/
                  (select count(*) from pocketlists_attachment pa where pa.item_id = i.id) attachments_count,
                  (select count(*) from pocketlists_comment pc where pc.item_id = i.id) comments_count,  
                  (select count(*) from pocketlists_item_link pil where pil.item_id = i.id) linked_entities_count  
                FROM {$this->table} i
                LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id
                /*LEFT JOIN (select i2.name, l2.*
                  from pocketlists_list l2
                         JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) pl ON pl.id = i.list_id*/
                 ";
    }

    /**
     * @param      $list_id
     * @param bool $tree
     *
     * @return array|mixed
     */
    public function getAllByList($list_id, $tree = true)
    {
        $sql = $this->getQuery()."
                WHERE i.list_id = i:lid
                ORDER BY i.parent_id, i.sort ASC, i.id DESC";

        return $this->getItems($sql, $list_id, $tree);
    }

    /**
     * @param      $list_id
     * @param bool $tree
     *
     * @return array|mixed
     */
    public function getUndoneByList($list_id, $tree = true)
    {
        $sql = $this->getQuery()."
                WHERE i.list_id = i:lid AND i.status = 0
                ORDER BY i.parent_id, i.sort ASC, i.id DESC";

        return $this->getItems($sql, $list_id, $tree);
    }

    /**
     * @param      $list_id
     * @param int  $offset
     * @param int  $limit
     * @param bool $tree
     *
     * @return array|mixed
     */
    public function getDoneByList($list_id, $offset = 0, $limit = 10, $tree = true)
    {
        $sql = $this->getQuery()."
                WHERE i.list_id = i:lid AND i.status > 0
                ORDER BY i.complete_datetime DESC, i.parent_id, i.sort ASC, i.id DESC
                LIMIT {$limit}
                OFFSET {$offset}";

        return $this->getItems($sql, $list_id, $tree);
    }

    /**
     * @param      $list_id
     * @param bool $tree
     *
     * @return array|mixed
     */
    public function getArchiveByList($list_id, $tree = true)
    {
        $sql = $this->getQuery()."
                WHERE i.list_id = i:lid AND i.status < 0
                ORDER BY i.parent_id, i.sort ASC, i.id DESC";

        return $this->getItems($sql, $list_id, $tree);
    }

    /**
     * @param $sql
     * @param $list_id
     * @param $tree
     *
     * @return array|mixed
     */
    private function getItems($sql, $list_id, $tree)
    {
//        $key = $this->getCacheKey($sql.serialize($list_id).$tree);
//
//        return $this->getFromCache(
//            $key,
//            function () use ($key, $sql, $list_id, $tree) {
                $items = $this->query(
                    $sql,
                    [
                        'lid'        => $list_id,
                        'contact_id' => wa()->getUser()->getId(),
                    ]
                )->fetchAll('id');

//                $items = self::generateModels($items);

//                $items = $this->extendItemData($items);

//                self::$cached[$key] = $tree ? $this->getTree($items, $tree) : $items;
//
//                return self::$cached[$key];
//            }
//        );

        return $items;
    }

    /**
     * @param $items
     * @param $tree
     *
     * @return array
     */
    private function getTree($items, $tree)
    {
        $result = [];

        if (!$items) {
            return $result;
        }

        foreach ($items as $id => $item) {
            $result[$item['id']] = $item;
            $result[$item['id']]['childs'] = [];
        }

        foreach ($result as $id => $item) {
            $result[$item['parent_id']]['childs'][$id] =& $result[$id];
        }
        if ($tree === true) {
            $result = isset($result[0]) ? $result[0]['childs'] : [];
        } elseif (is_numeric($tree)) {
            $result = isset($result[$tree]) ? [$tree => $result[$tree]] : [];
        }

        return $result;
    }

    /**
     * @param      $items
     * @param bool $edit
     *
     * @return array|bool|mixed
     * @throws waDbException
     * @throws waException
     */
    public function extendItemData($items, $edit = false)
    {
        if (!is_array($items) && !$items instanceof pocketlistsItemModel) {
            return false;
        }

        $is_array = true;
        if (isset($items['id']) || $items instanceof pocketlistsItemModel) {
            $is_array = false;
            $items = [$items];
        }
        foreach ($items as &$item) {
            if ($item['contact_id']) {
                $user = new waContact($item['contact_id']);
                $item['contact'] = pocketlistsHelper::getContactData($user);
            } else {
                $item['contact'] = pocketlistsHelper::getContactData(new waContact());
            }
            if ($item['assigned_contact_id']) {
                $user = new waContact($item['assigned_contact_id']);
                $item['assigned_contact'] = pocketlistsHelper::getContactData($user);
            }
            if ($item['complete_contact_id']) {
                $user = new waContact($item['complete_contact_id']);
                $item['complete_contact'] = pocketlistsHelper::getContactData($user);
            }

            $am = new pocketlistsAttachmentModel();
            $item['attachments'] = $am->getByField('item_id', $item['id'], true);

            $this->addChatData($item);

            $this->addPriorityData($item);

            $age_time = time() - max(
                    !empty($item['update_datetime']) ? strtotime($item['update_datetime']) : 0,
                    strtotime($item['create_datetime'])
                );
            $item['age_time'] = $age_time < 1 ? '' : pocketlistsHelper::getDatetimeBySeconds($age_time);

            if (!$edit) {
                $this->prepareOutput($item);
            }
        }

        return ($is_array || !$items) ? $items : reset($items);
    }

    /**
     * @param $item
     *
     * @return mixed
     */
    public function prepareOutput(&$item)
    {
        foreach (['name', 'note'] as $param) {
            $item[$param.'_original'] = $item[$param];
            $item[$param] = pocketlistsNaturalInput::matchLinks($item[$param]);
        }

        if (isset($item->chat['comments']) && is_array($item->chat['comments'])) {
            foreach ($item['chat']['comments'] as $i => $comment) {
                $item->chat['comments'][$i]['comment_original'] = $comment['comment'];
                $item->chat['comments'][$i]['comment'] = pocketlistsNaturalInput::matchLinks($comment['comment']);
            }
        }

        return $item;
    }

    /**
     * @param $item
     *
     * @return mixed
     */
    public function addPriorityData(&$item)
    {
        $item['due_date'] = isset($item['due_date']) ? $item['due_date'] : null;
        $item['due_datetime'] = isset($item['due_datetime']) ? $item['due_datetime'] : null;

        $item['calc_priority'] = max(
            pocketlistsHelper::calcPriorityOnDueDate($item['due_date'], $item['due_datetime']),
            isset($item['priority']) ? $item['priority'] : pocketlistsItemModel::PRIORITY_NORM
        );

        return $item;
    }

    /**
     * @param pocketlistsItemModel $item
     *
     * @throws waDbException
     * @throws waException
     */
    private function addChatData(&$item)
    {
        $cm = new pocketlistsCommentModel();
        $chat = $cm->getAllByItems($item['id']);
        if (empty($chat[$item['id']])) {
            return;
        }
        foreach ($chat[$item['id']] as $comment) {
            $item->setChatComment($comment);
        }
    }

    public function setChatComment($comment)
    {
        $comments = $this->chat['comments'];
        $comments[$comment['id']] = pocketlistsCommentModel::extendData($comment);

        $this->chat = [
            'current_user' => [
                'username' => wa()->getUser()->getName(),
                'userpic'  => wa()->getUser()->getPhoto(20),
            ],
            'comments'     => $comments,
        ];
    }

    /**
     * @param $items
     *
     * @return mixed
     */
    public function getProperSort($items)
    {
        usort($items, [$this, 'compare_for_proper_sort']);

        return $items;
    }

    /**
     * @param $i1
     * @param $i2
     *
     * @return int
     */
    private function compare_for_proper_sort($i1, $i2)
    {
        if ($i1['calc_priority'] < $i2['calc_priority']) {
            return 1;
        }

        if ($i1['calc_priority'] > $i2['calc_priority']) {
            return -1;
        }

        $date1 = !empty($i1['due_datetime'])
            ? strtotime($i1['due_datetime'])
            : (!empty($i1['due_date']) ? strtotime($i1['due_date']) : null);

        $date2 = !empty($i2['due_datetime'])
            ? strtotime($i2['due_datetime'])
            : (!empty($i2['due_date']) ? strtotime($i2['due_date']) : null);

        // check due_date
        if ($date1 && $date2) { // check both dates
            if ($date1 < $date2) {
                return -1;
            }

            if ($date1 > $date2) {
                return 1;
            }

            return 0;
        }

        if ($date1 && !$date2) {
            return -1;
        }

        if (!$date1 && $date2) {
            return 1;
        }

        $date1 = !empty($i1['update_datetime']) ? strtotime($i1['update_datetime']) : null;

        $date2 = !empty($i2['update_datetime']) ? strtotime($i2['update_datetime']) : null;

        // check update_datetime
        if ($date1 && $date2) { // check both dates
            if ($date1 < $date2) {
                return -1;
            }

            if ($date1 > $date2) {
                return 1;
            }

            return 0;
        }

        if ($date1 && !$date2) {
            return -1;
        }

        if (!$date1 && $date2) {
            return 1;
        }

        return 0;
    }

    /**
     * @param $list_id
     *
     * @return array
     */
    public function sortItems($list_id)
    {
        $sql = $this->getQuery()."WHERE
                  i.list_id = i:id
                  AND i.status = 0
                  ORDER BY i.id DESC
                /*GROUP BY i.parent_id, i.id*/";
//        $items = $this->getItems($sql, $list_id, false);
        $items = $this->query($sql, ['id' => $list_id, 'contact_id' => wa()->getUser()->getId()])->fetchAll();

        $items = $this->getProperSort($items);
        $sort = 0;
        foreach ($items as $item) {
            $this->updateById(
                $item['id'],
                [
//                    'update_datetime' => date("Y-m-d H:i:s"),
                    'sort' => $sort++,
                ]
            );
        }

        return $this->getTree($items, true);
    }

    /**
     * @param $contact_ids
     *
     * @return array
     */
    public function getAssignedItemsCountAndNames($contact_ids)
    {
        if (!is_array($contact_ids)) {
            $contact_ids = [$contact_ids];
        }

        $result = [];

        $filter = new pocketlistsStrategyItemFilterAndSort();

        foreach ($contact_ids as $contact_id) {
            $contact = new pocketlistsContact(new waContact($contact_id));

            $items = pl2()->getEntityFactory(pocketlistsItem::class)->findAssignedOrCompletesByContact($contact);

            $filter->setItems($items)->filterDoneUndone();

            foreach ($items[0] as $item) {
//                $this->addPriorityData($item);
                $result[$contact_id]['item_names'][] = $item['name'];
                $result[$contact_id]['item_max_priority'] = max(
                    !empty($result[$contact_id]['item_max_priority']) ?
                        $result[$contact_id]['item_max_priority'] : pocketlistsItem::PRIORITY_NORM,
                    $item['calc_priority']
                );
            }
        }

        return $result;
    }

    /**
     * @param array $contact_ids
     *
     * @return array
     */
    public function getLastActivities($contact_ids = [])
    {
        $by_contact = "";
        if ($contact_ids && !is_array($contact_ids)) {
            $contact_ids = [$contact_ids];
            $by_contact = " WHERE t.contact_id IN (i:contact_id)";
        }

        // ох что-то я сомневаюсь
        $q = "SELECT
              MAX(t.last_date) last_activity_datetime,
              t.contact_id contact_id
            FROM (
                  SELECT
                    i.complete_contact_id contact_id,
                    max(i.complete_datetime) last_date
                  FROM {$this->table} i
                  GROUP BY i.complete_contact_id

                  UNION

                  SELECT
                    i.contact_id contact_id,
                    max(i.create_datetime) last_date
                  FROM {$this->table} i
                  GROUP BY i.contact_id
                  
                  UNION

                  SELECT
                    c.contact_id contact_id,
                    max(c.create_datetime) last_date
                  FROM pocketlists_comment c
                  GROUP BY c.contact_id
              ) t
            {$by_contact}
            GROUP BY t.contact_id";

        return $this->query(
            $q,
            ['contact_id' => $contact_ids]
        )->fetchAll('contact_id', 1);
    }

    /**
     * @param $contact_id
     *
     * @return array
     */
    public function getAssignedOrCompletesByContactItems($contact_id)
    {

        $lists = [];
        pocketlistsRBAC::filterListAccess($lists, $contact_id);
        $list_sql = pocketlistsRBAC::filterListAccess($lists);
        $q = "SELECT
                  i.id id,
                  i.parent_id parent_id,
                  i.has_children has_children,
                  i.name name,
                  i.note note,
                  i.status status,
                  i.priority priority,
                  i.calc_priority calc_priority,
                  i.contact_id contact_id,
                  i.create_datetime create_datetime,
                  i.due_date due_date,
                  i.due_datetime due_datetime,
                  i.complete_datetime complete_datetime,
                  i.complete_contact_id complete_contact_id,
                  i.assigned_contact_id assigned_contact_id,
                  i.key_list_id key_list_id,
                  l.id list_id,
                  l.icon list_icon,
                  l.color list_color,
                  l.name list_name,
                  IF(uf.contact_id, 1, 0) favorite
                FROM {$this->table} i
                LEFT JOIN (select i2.name, l2.*
                          from pocketlists_list l2
                                 JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id
                LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:user_contact_id AND uf.item_id = i.id
                WHERE
                  (
                    i.assigned_contact_id = i:contact_id AND i.status >= 0 /* assigned to contact no matter who it completed */
                    OR i.contact_id = i:contact_id AND i.status >= 0 /* created by contact (completed and not) */
                    OR i.complete_contact_id = i:contact_id AND i.status > 0 /* completed by contact */
                  )
                  AND l.archived = 0
                  AND {$list_sql}
                GROUP BY id
                ORDER BY
                  i.status,
                  (i.complete_datetime IS NULL), i.complete_datetime DESC";
        $items = $this->query(
            $q,
            [
                'contact_id'      => $contact_id,
                'list_ids'        => $lists,
                'user_contact_id' => wa()->getUser()->getId(),
            ]
        )->fetchAll();

//        $items = self::generateModels($items);

        $results = [
            0 => [],
            1 => [],
        ];
//
        if ($items) {
            foreach ($items as $id => $item) {
                $results[$item['status']][$id] = $item;
            }
        }
//
        return [
            0 => $results[0],
            1 => $results[1],
        ];

        return $items;
    }

    /**
     * @param string|bool $app
     * @param string|bool $entity_type
     * @param int|bool    $entity_id
     *
     * @return array
     */
    public function getAppItems($app = false, $entity_type = false, $entity_id = false, $date = false)
    {
        $lists = [];
        $contact_id = wa()->getUser()->getId();
//        pocketlistsRBAC::filterListAccess($lists, $contact_id);
        $list_sql = 1;//pocketlistsRBAC::filterListAccess($lists);

        $appSql = '';
        if ($app !== false) {
            $appSql = 'AND pil.app = s:app';
        }

        $appTypeSql = '';
        if ($entity_type !== false) {
            $appTypeSql = 'AND pil.entity_type = s:type';
        }

        $entityIdSql = '';
        if ($entity_type !== false) {
            $entityIdSql = 'AND pil.entity_id = i:entity_id';
        }

        $dateSql = '';
        if ($date) {
            $dateSql = "AND ((i.status = 0 AND (i.due_date = s:date OR DATE(i.due_datetime) = s:date)) OR (i.status > 0 AND DATE(i.complete_datetime) = s:date)) /* with due date or completed this day */";
        }

        $q = "SELECT
                  i.id id,
                  i.parent_id parent_id,
                  i.has_children has_children,
                  i.name name,
                  i.note note,
                  i.status status,
                  i.priority priority,
                  i.calc_priority calc_priority,
                  i.contact_id contact_id,
                  i.create_datetime create_datetime,
                  i.due_date due_date,
                  i.due_datetime due_datetime,
                  i.complete_datetime complete_datetime,
                  i.complete_contact_id complete_contact_id,
                  i.assigned_contact_id assigned_contact_id,
                  i.key_list_id key_list_id,
                  l.id list_id,
                  l.icon list_icon,
                  l.color list_color,
                  l.name list_name,
                  IF(uf.contact_id, 1, 0) favorite
                FROM {$this->table} i
                LEFT JOIN (select i2.name, l2.*
                          from pocketlists_list l2
                                 JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id
                LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:user_contact_id AND uf.item_id = i.id
                JOIN pocketlists_item_link pil ON pil.item_id = i.id {$appSql} {$appTypeSql} {$entityIdSql}
                WHERE
                  l.archived = 0
                  AND {$list_sql}
                  {$dateSql}
                GROUP BY i.id
                ORDER BY
                  i.status,
                  (i.complete_datetime IS NULL), i.complete_datetime DESC";

        $items = $this->query(
            $q,
            [
                'contact_id'      => $contact_id,
                'list_ids'        => $lists,
                'user_contact_id' => wa()->getUser()->getId(),
                'app'             => $app,
                'type'            => $entity_type,
                'entity_id'       => $entity_id,
                'date'            => $date,
            ]
        )->fetchAll();

        $items = self::generateModels($items);

        $results = [
            0 => [],
            1 => [],
        ];

        if ($items) {
            $items = $this->extendItemData($items);

            foreach ($items as $id => $item) {
                $results[$item['status']][$id] = $item;
            }
        }

        return [
            0 => $results[0],
            1 => $results[1],
        ];
    }

    /**
     * @param $contact_id
     * @param $when
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    public function getDailyRecapItems($contact_id, $when)
    {
        $now = time();
        $today = date("Y-m-d");
        $tomorrow = date("Y-m-d", strtotime("+1 day", $now));
        $seven_days = date("Y-m-d", strtotime("+7 days", $now));
        switch ($when) {
            case pocketlistsUserSettings::DAILY_RECAP_FOR_TODAY:
                $when = " AND (i.due_date <= '".$today."')";
                break;
            case pocketlistsUserSettings::DAILY_RECAP_FOR_TODAY_AND_TOMORROW:
                $when = " AND (i.due_date <= '".$tomorrow."')";
                break;
            case pocketlistsUserSettings::DAILY_RECAP_FOR_NEXT_7_DAYS:
                $when = " AND (i.due_date <= '".$seven_days."')";
                break;
        }

        $lists = pocketlistsRBAC::getAccessListForContact($contact_id);
        $list_sql = $lists ? " AND (l.id IN (i:list_ids) OR l.id IS NULL) " : " AND l.id IS NULL ";

        $q = "SELECT
                i.*
              FROM {$this->table} i
              LEFT JOIN pocketlists_list l ON (l.id = i.list_id  OR l.id = i.key_list_id)
              WHERE
                (
                  (i.assigned_contact_id = i:contact_id) /* + items assigned to me */
                  OR i.priority > ".pocketlistsItemModel::PRIORITY_NORM." /* + items with priority */
                  OR
                  (
                    /*((i.due_date IS NOT NULL OR i.due_datetime IS NOT NULL) AND i.list_id IS NULL AND i.key_list_id IS NULL AND i.contact_id = 7)
                    OR
                    ((i.due_date IS NOT NULL OR i.due_datetime IS NOT NULL) AND (i.list_id IS NOT NULL OR i.key_list_id IS NOT NULL)) */
                    (i.due_date IS NOT NULL OR i.due_datetime IS NOT NULL) /* + items with due */
                    AND
                    IF(i.list_id IS NULL AND i.key_list_id IS NULL, i.contact_id = i:contact_id, i.contact_id > 0) /* + and if item is from NULL-list check contact_id */
                  )
                  OR (i.contact_id = i:contact_id AND i.list_id IS NULL AND i.key_list_id IS NULL) /* + my items from NULL-list */
                )
                AND i.status = 0 /* ONLY not completed items */
                AND l.archived = 0 /* ONLY not archived items */
                AND (i.assigned_contact_id = i:contact_id OR i.assigned_contact_id IS NULL) /* ONLY assigned to me or noone */
                {$list_sql}
                {$when}";

        $items = $this->query(
            $q,
            [
                'contact_id' => $contact_id,
                'list_ids'   => $lists,
            ]
        )->fetchAll();
//        foreach ($items as $id => $item) {
//            $items[$id] = $this->extendItemData($item);
//        }

        return $items;
    }

    /**
     * @param $name
     * @param $datetime
     *
     * @return array
     */
    public function getItemByNameAndCreatedDatetime($name, $datetime)
    {
        return $this->query(
            "SELECT * FROM {$this->table} WHERE name = s:name AND 
 create_datetime BETWEEN (s:d1, s:d2) LIMIT 1",
            [
                'name' => $name,
                'd1'   => date('Y-m-d H:i:s', strtotime($datetime) - 60),
                'd2'   => date('Y-m-d H:i:s', strtotime($datetime) + 60),
            ]
        )->fetch();
    }

    public function getLastActivityItems($user_last_activity)
    {
        $user_id = wa()->getUser()->getId();
        $lists = [];
        $list_sql = pocketlistsRBAC::filterListAccess($lists);
        $q = "SELECT 
                     i.id item_id,
                     i.list_id list_id,
                     i.status status,
                     i.contact_id contact_id,
                     i.assigned_contact_id assigned_contact_id,
                     i.complete_contact_id complete_contact_id,
                     l.archived list_archived
                FROM pocketlists_item i
                LEFT JOIN pocketlists_list l ON l.id = i.list_id
                WHERE 
                    {$list_sql}
                    AND (
                      i.create_datetime > s:user_last_activity 
                      OR i.complete_datetime > s:user_last_activity
                    )";

        $items = $this->query(
            $q,
            [
                'list_ids'           => $lists,
                'user_last_activity' => $user_last_activity,
            ]
        )->fetchAll();

        $result = [
            'list'    => [],
            'team'    => [],
            'archive' => 0,
            'logbook' => 0,
        ];
        foreach ($items as $item) {
            if ($item['list_id'] && $item['contact_id'] != $user_id && $item['status'] == 0) {
                if (!isset($result['list'][$item['list_id']])) {
                    $result['list'][$item['list_id']] = 0;
                }
                $result['list'][$item['list_id']]++;
            }
            if ($item['assigned_contact_id'] && $item['assigned_contact_id'] != $user_id && $item['status'] == 0) {
                if (!isset($result['team'][$item['assigned_contact_id']])) {
                    $result['team'][$item['assigned_contact_id']] = 0;
                }
                $result['team'][$item['assigned_contact_id']]++;
            }
            if ($item['list_archived'] && $item['status'] == 0) {
                $result['archive']++;
            }
            if ($item['status'] && $item['complete_contact_id'] != $user_id) {
                $result['logbook']++;
            }
        }

        return $result;
    }

    /**
     * @return array|pocketlistsItemLinkModel[]
     * @throws waException
     */
    public function getLinkedEntities()
    {
        if ($this->linkedEntities === null) {
            /** @var pocketlistsItemLinkFactory $factory */
            $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getEntityFactory(pocketlistsItemLink::class);
            $this->linkedEntities = $factory->getForItem($this) ?: [];
        }

        return $this->linkedEntities;
    }

    /**
     * @param string $app
     *
     * @return int
     */
    public function getCountForApp($app)
    {
        $count = (int)$this->query(
            "SELECT COUNT(lid) cnt
             FROM (
                SELECT l.item_id lid 
                FROM {$this->table} i 
                JOIN pocketlists_item_link l ON i.id = l.item_id AND i.status = 0
                WHERE app = s:app 
                GROUP BY l.item_id) t",
            ['app' => $app]
        )->fetchField('cnt');

        return $count;
    }

    public function getAllByPocket($id)
    {
        if (!$id) {
            return false;
        }

        $result = $this->query(
            "SELECT i.*
             FROM {$this->table} i
             JOIN pocketlists_list l ON l.id = i.list_id
             WHERE l.pocket_id = i:pocket_id",
            ['pocket_id' => $id]
        );

        return $result->fetchAll('id');
    }

    /**
     * обновляем приоритеты в зависимости от текущего времени
     */
    public function updateCalcPriority()
    {
        $itemsWithDue = $this->where('due_date is not null or due_datetime is not null')->fetchAll('id');
        foreach ($itemsWithDue as $item) {
            $newCalcPriority = max(
                pocketlistsHelper::calcPriorityOnDueDate($item['due_date'], $item['due_datetime']),
                $item['priority']
            );

            if ($item['calc_priority'] != $newCalcPriority) {
                $this->updateById($item['id'], ['calc_priority' => $newCalcPriority]);
            }
        }
    }

    /**
     * @param array|int $value
     *
     * @return array|mixed|null
     */
    public function getById($value)
    {
        $all = !is_array($this->id) && is_array($value);
        $sql = $this->getQuery().'
                WHERE i.id IN (i:ids)';

        $items = $this->query($sql, ['contact_id' => pl2()->getUser()->getContact()->getId(), 'ids' => $value])->fetchAll('id');

        return $all ? $items : reset($items);
    }
}
