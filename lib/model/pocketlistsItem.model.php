<?php

/**
 * Class pocketlistsItemModel
 */
class pocketlistsItemModel extends pocketlistsModel
{
    const PRIORITY_NORM       = 0;
    const PRIORITY_GREEN      = 1;
    const PRIORITY_YELLOW     = 2;
    const PRIORITY_RED        = 3;
    const PRIORITY_BLACK      = 4;
    const PRIORITY_BURNINHELL = 5;

    /**
     * @var string
     */
    protected $table = 'pocketlists_item';

    /**
     * @return array
     */
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
     * @throws waDbException
     * @throws waException
     */
    public function getCompletedItems($contact_id = false, $date_range = false)
    {
        return $this->getLogbookItems($contact_id, $date_range);
    }

    /**
     * @param bool $contact_id
     * @param bool $date_range
     * @param bool $completed
     * @param int  $start
     * @param int  $limit
     *
     * @return array
     * @throws waDbException
     * @throws waException
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

        return $items;
    }

    /**
     * @param int   $contact_id
     * @param array $dateBounds
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    public function getToDo($contact_id = 0, $dateBounds = [], $status = null, $offset = 0, $limit = 0)
    {
        $dateBounds = $dateBounds ?: [];

        if (!is_array($dateBounds)) {
            $dateBounds = [$dateBounds];
        }

        if (!$contact_id) {
            $contact_id = wa()->getUser()->getId();
        }

        $items = $this->fetchTodo($contact_id, $dateBounds, $calc_priority = [], $status, $offset, $limit);

        return $items;
    }

    /**
     * @param $contact_id
     * @param $dateBounds
     *
     * @return array
     *
     * @throws waDbException
     * @throws waException
     */
    public function fetchTodo(
        $contact_id,
        $dateBounds = [],
        array $calc_priority = [],
        $status = null,
        $offset = 0,
        $limit = 0
    ) {
        // get to-do items only from accessed pockets
        $lists = pocketlistsRBAC::getAccessListForContact($contact_id);

        $sqlParts = $this->getTodoSqlComponents($contact_id, $dateBounds, $lists, $calc_priority);

        if ($status !== null) {
            $sqlParts['and'][] = 'i.status = '.$status;
        }

        $or_sql = implode("\n OR ", $sqlParts['or']);
        $and_sql = implode("\n AND ", $sqlParts['and']);
        $join_sql = implode("\n LEFT JOIN ", $sqlParts['join']);
        $select_sql = implode(",\n", $sqlParts['select']);

        $sql = sprintf(
            'SELECT
                  %s
                FROM %s i
                %s
                WHERE
                  (%s) 
                  AND 
                  %s
                GROUP BY i.id
                ORDER BY
                  i.status,
                  (i.complete_datetime IS NULL), i.complete_datetime DESC,
                  i.id DESC
                %s
                ',
            $select_sql,
            $this->table,
            $join_sql,
            $or_sql,
            $and_sql,
            $offset || $limit ? sprintf('limit %d, %d', $offset, $limit) : ''
        );

//        $sql = $this->buildSqlComponents($sqlParts, $limit, $offset);

        $items = $this->query(
            $sql,
            [
                'list_ids'      => $lists,
                'contact_id'    => $contact_id,
                'date'          => isset($dateBounds[0]) ? $dateBounds[0] : '',
                'date2'         => isset($dateBounds[1]) ? $dateBounds[1] : '',
                'calc_priority' => $calc_priority,
            ]
        )->fetchAll();

        return $items;
    }

    /**
     * @param int   $contact_id
     * @param array $date
     * @param array $calc_priority
     * @param null  $status
     *
     * @return bool|mixed
     * @throws waDbException
     * @throws waException
     */
    public function countTodo($contact_id, $date = [], array $calc_priority = [], $status = null)
    {
        // get to-do items only from accessed pockets
        $lists = pocketlistsRBAC::getAccessListForContact($contact_id);

        $sqlParts = $this->getTodoSqlComponents($contact_id, $date, $lists, $calc_priority);

        $sqlParts['select'] = ['count(i.id)'];
        if ($status !== null) {
            $sqlParts['and'][] = 'i.status = '.$status;
        }

        $or_sql = implode("\n OR ", $sqlParts['or']);
        $and_sql = implode("\n AND ", $sqlParts['and']);
        $join_sql = implode("\n LEFT JOIN ", $sqlParts['join']);
        $select_sql = implode(",\n", $sqlParts['select']);

        $sql = "SELECT
                  {$select_sql}
                FROM {$this->table} i
                {$join_sql}
                WHERE
                  ({$or_sql}) 
                  AND 
                  {$and_sql}";

        $items = $this->query(
            $sql,
            [
                'list_ids'      => $lists,
                'contact_id'    => $contact_id,
                'date'          => $date,
                'calc_priority' => $calc_priority,
            ]
        )->fetchField();

        return $items;
    }

    /**
     * @param       $contact_id
     * @param       $dateBounds
     * @param       $lists
     * @param array $calc_priority
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    protected function getTodoSqlComponents($contact_id, array $dateBounds, $lists, array $calc_priority = [])
    {
        $sqlParts = [
            'select' => [],
            'join'   => [],
            'and'    => [],
            'or'     => [],
        ];

        $sqlParts['select'] = [
            'i.*',
            'IF(uf.contact_id, 1, 0) favorite',
            'l.color list_color',
            '(select count(*) from pocketlists_attachment pa where pa.item_id = i.id) attachments_count',
            '(select count(*) from pocketlists_comment pc where pc.item_id = i.id) comments_count',
            '(select count(*) from pocketlists_item_link pil where pil.item_id = i.id) linked_entities_count',
        ];

        $sqlParts['join'] = [
            '',
            'pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id',
            '(select i2.name, l2.*
                  from pocketlists_list l2
                         JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id',
        ];

        $sqlParts['and'] = [
            sprintf('(%s OR l.id IS NULL)', pocketlistsRBAC::filterListAccess($lists, $contact_id)),
            '(l.id is null OR (l.id is not null and l.archived = 0))',
            // get to-do items only from accмфп essed pockets
        ];

        $sqlParts['or'] = [
            '(i.list_id IS NULL AND i.key_list_id IS NULL AND i.contact_id = i:contact_id) /* My to-dos to self */',
            '(i.key_list_id IS NULL AND i.assigned_contact_id = i:contact_id) /* To-dos assigned to me by other users */',
        ];

        if ($dateBounds) {
            if (isset($dateBounds[1])) {
                $sqlParts['and'][] = '((i.status = 0 AND (i.due_date BETWEEN s:date AND s:date2 OR DATE(i.due_datetime) BETWEEN s:date AND s:date2)) OR (i.status > 0 AND DATE(i.complete_datetime) BETWEEN s:date AND s:date2)) /* with due date or completed this day */';
            } elseif (isset($dateBounds[0])) {
                $sqlParts['and'][] = '((i.status = 0 AND (i.due_date = s:date OR DATE(i.due_datetime) = s:date)) OR (i.status > 0 AND DATE(i.complete_datetime) = s:date)) /* with due date or completed this day */';
            }
        }

        $us = new pocketlistsUserSettings($contact_id);

        switch ($us->myToDosCreatedByMe()) {
            case pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_ME_IN_SHARED_ANY_LIST:
                $sqlParts['or'][] = '(i.list_id IS NOT NULL AND i.key_list_id IS NULL AND i.contact_id = i:contact_id) /* To-dos created by me in shared lists in just any list */';
                break;

            case pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_ME_IN_SHARED_FAVORITE_LISTS:
                $sqlParts['or'][] = '(i.list_id IS NOT NULL AND i.key_list_id IS NULL AND i.contact_id = i:contact_id AND uf2.contact_id = i:contact_id) /* To-dos created BY other users IN shared lists only in lists which I marked as favorite*/';
                break;
        }

        switch ($us->myToDosCreatedByOthers()) {
            case pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_OTHER_IN_SHARED_LISTS_FAVORITE_LISTS:
                $sqlParts['or'][] = '(i.list_id IS NOT NULL AND i.key_list_id IS NULL AND i.contact_id <> i:contact_id AND uf2.contact_id = i:contact_id) /* To-dos created BY other users IN shared lists only in lists which I marked as favorite*/';
                break;

            case pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_OTHER_IN_SHARED_LISTS_GREEN_YELLOW_RED_ALL_LISTS:
                $tomorrow = date('Y-m-d', strtotime('+1 day'));
                $day_after_tomorrow = date("Y-m-d", strtotime('+2 day'));
                $sqlParts['or'][] = sprintf(
                    "(i.list_id IS NOT NULL AND i.key_list_id IS NULL AND i.contact_id <> i:contact_id AND (i.due_date <= '%s' OR i.due_datetime < '%s' OR i.priority IN (%s))) /* To-dos created BY other users IN shared lists all Green, Yellow, and Red to-dos from all lists*/",
                    $tomorrow,
                    $day_after_tomorrow,
                    implode(
                        ',',
                        [
                            self::PRIORITY_GREEN,
                            self::PRIORITY_YELLOW,
                            self::PRIORITY_RED,
                            self::PRIORITY_BLACK,
                            self::PRIORITY_BURNINHELL,
                        ]
                    )
                );
                break;
        }

        if ($us->myToDosCreatedByOthers(
            ) == pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_OTHER_IN_SHARED_LISTS_FAVORITE_LISTS
            || $us->myToDosCreatedByMe() == pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_ME_IN_SHARED_FAVORITE_LISTS) {
            $sqlParts['join'][] = 'pocketlists_user_favorites uf2 ON uf2.contact_id = i:contact_id AND uf2.item_id = l.key_item_id';

            $sqlParts['select'][] = 'IF(uf2.contact_id, 1, 0) favorite_list';
        }

        $sqlParts['and'][] = '(i.assigned_contact_id = i:contact_id OR i.assigned_contact_id IS NULL)';

        if ($calc_priority) {
            $sqlParts['and'][] = '(i.calc_priority in (i:calc_priority))';
        }

        return $sqlParts;
    }

    /**
     * @param bool $contact_id
     * @param bool $date
     * @param bool $date2
     * @param null $status
     *
     * @return int
     * @throws waDbException
     * @throws waException
     */
    public function getFavoritesCount($contact_id = false, $date = false, $date2 = false, $status = null)
    {
        if (!$contact_id) {
            $contact_id = wa()->getUser()->getId();
        }
        // get to-do items only from accessed pockets
        $lists = pocketlistsRBAC::getAccessListForContact($contact_id);

        $sqlParts = $this->getFavoritesSqlComponents($lists, $date, $date2, $status);
        $sqlParts['select'] = ['count(i.id)'];
        $sqlParts['order by'] = [];

        $sql = $this->buildSqlComponents($sqlParts);

        $itemsCount = $this->query(
            $sql,
            [
                'list_ids'   => $lists,
                'date'       => $date,
                'date2'      => $date2,
                'contact_id' => $contact_id,
            ]
        )->fetchField();

        return (int)$itemsCount;
    }

    /**
     * @param bool $contact_id
     * @param bool $date
     * @param bool $date2
     * @param null $status
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    private function getFavoritesSqlComponents($lists, $date = false, $date2 = false, $status = null)
    {
        $sqlParts = $this->getQueryComponents();

        if ($lists) {
            $sqlParts['where']['and'][] = '(l.id IN (i:list_ids) OR l.id IS NULL) /* ONLY items from accessed pockets or NULL-list items */';
        } else {
            $sqlParts['where']['and'][] = 'l.id IS NULL /* ONLY items from NULL-list items */';
        }

        if ($date) {
            $sqlParts['where']['and'][] = '((i.status = 0 AND (i.due_date = s:date OR DATE(i.due_datetime) = s:date)) OR (i.status > 0 AND DATE(i.complete_datetime) = s:date)) /* with due date or completed this day */';
        }

        if ($date2) {
            $sqlParts['where']['and'][] = '((i.status = 0 AND (i.due_date BETWEEN s:date AND s:date2 OR DATE(i.due_datetime) BETWEEN s:date AND s:date2)) OR (i.status > 0 AND DATE(i.complete_datetime) BETWEEN s:date AND s:date2)) /* with due date or completed this day */';
        }

        if ($status !== null) {
            $sqlParts['where']['and'][] = 'i.status = '.$status;
        }

        $sqlParts['join']['l'] = 'left join (select i2.name, l2.*
                          from pocketlists_list l2
                                 JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id AND l.archived = 0';

        $sqlParts['order by'][] = 'i.status';
        $sqlParts['order by'][] = '(i.complete_datetime IS NULL)';
        $sqlParts['order by'][] = 'i.complete_datetime DESC';

        $sqlParts['where']['and'][] = 'uf.item_id IS NOT NULL';

        return $sqlParts;
    }

    /**
     * @param bool $contact_id
     * @param bool $date
     * @param bool $date2
     * @param null $status
     * @param int  $offset
     * @param int  $limit
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    public function getFavorites(
        $contact_id = false,
        $date = false,
        $date2 = false,
        $status = null,
        $offset = 0,
        $limit = 0
    ) {
        if (!$contact_id) {
            $contact_id = wa()->getUser()->getId();
        }
        // get to-do items only from accessed pockets
        $lists = pocketlistsRBAC::getAccessListForContact($contact_id);

        $sqlParts = $this->getFavoritesSqlComponents($lists, $date, $date2, $status);
        $sql = $this->buildSqlComponents($sqlParts, $limit, $offset);

        $items = $this->query(
            $sql,
            [
                'list_ids'   => $lists,
                'date'       => $date,
                'date2'      => $date2,
                'contact_id' => $contact_id,
            ]
        )->fetchAll();

        return $items;
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
     * @return array
     */
    private function getQueryComponents()
    {
        return [
            'select'   => [
                '*'                     => 'i.*',
                'favorite'              => 'IF(uf.contact_id, 1, 0) favorite',
                'attachments_count'     => '(select count(*) from pocketlists_attachment pa where pa.item_id = i.id) attachments_count',
                'comments_count'        => '(select count(*) from pocketlists_comment pc where pc.item_id = i.id) comments_count',
                'linked_entities_count' => '(select count(*) from pocketlists_item_link pil where pil.item_id = i.id) linked_entities_count',
            ],
            'from'     => ['i' => "{$this->table} i"],
            'join'     => [
                'uf' => 'left join pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id',
            ],
            'where'    => [
                'and' => [],
                'or'  => [],
            ],
            'group by' => [],
            'order by' => [],
        ];
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
        $items = $this->query(
            $sql,
            [
                'lid'        => $list_id,
                'contact_id' => wa()->getUser()->getId(),
            ]
        )->fetchAll('id');

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
     * @param      $listSql
     * @param null $status
     *
     * @return array
     */
    private function getAssignedOrCompletesByContactQueryComponents($listSql, $status = null)
    {
        $sqlParts = $this->getQueryComponents();

        $sqlParts['join'][] = 'LEFT JOIN (select i2.name, l2.*
                          from pocketlists_list l2
                                 JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id AND l.archived = 0';

        $sqlParts['where']['and'][] = '(
                    i.assigned_contact_id = i:contact_id AND i.status >= 0 /* assigned to contact no matter who it completed */
                    OR i.contact_id = i:contact_id AND i.status >= 0 /* created by contact (completed and not) */
                    OR i.complete_contact_id = i:contact_id AND i.status > 0 /* completed by contact */
                  )';

        $sqlParts['where']['and'][] = $listSql;

        if ($status !== null) {
            $sqlParts['where']['and'][] = sprintf('i.status = %d', $status);
        }

        return $sqlParts;
    }

    /**
     * @param int      $contact_id
     * @param null|int $status
     * @param int      $limit
     * @param int      $offset
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    public function getAssignedOrCompletesByContactItems($contact_id, $status = null, $limit = 0, $offset = 0)
    {
        $lists = [];
        pocketlistsRBAC::filterListAccess($lists, $contact_id);
        $list_sql = pocketlistsRBAC::filterListAccess($lists);

        $sqlParts = $this->getAssignedOrCompletesByContactQueryComponents($list_sql, $status);

        $q = $this->buildSqlComponents($sqlParts, $limit, $offset);

        $items = $this->query(
            $q,
            [
                'contact_id'      => $contact_id,
                'list_ids'        => $lists,
                'user_contact_id' => wa()->getUser()->getId(),
            ]
        )->fetchAll();

        return $items;
    }

    /**
     * @param $contact_id
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    public function countAssignedOrCompletesByContactItems($contact_id)
    {
        $lists = [];
        pocketlistsRBAC::filterListAccess($lists, $contact_id);
        $list_sql = pocketlistsRBAC::filterListAccess($lists);

        $sqlParts = $this->getAssignedOrCompletesByContactQueryComponents($list_sql, pocketlistsItem::STATUS_UNDONE);
        $sqlParts['select'] = [
            'count(i.id) count_items',
            'if(max(i.calc_priority) is null, 0, max(i.calc_priority)) item_max_priority'
        ];

        $q = $this->buildSqlComponents($sqlParts);

        $items = $this->query(
            $q,
            [
                'contact_id' => $contact_id,
                'list_ids'   => $lists,
            ]
        )->fetchAssoc();

        return $items;
    }

    /**
     * @param $contact_id
     *
     * @return int
     * @throws waDbException
     * @throws waException
     */
    public function countAssignedOrCompletesDoneByContactItems($contact_id)
    {
        $lists = [];
        pocketlistsRBAC::filterListAccess($lists, $contact_id);
        $list_sql = pocketlistsRBAC::filterListAccess($lists);

        $sqlParts = $this->getAssignedOrCompletesByContactQueryComponents($list_sql, pocketlistsItem::STATUS_DONE);
        $sqlParts['select'] = ['count(i.id) count_items'];

        $q = $this->buildSqlComponents($sqlParts);

        $items = $this->query(
            $q,
            [
                'contact_id' => $contact_id,
                'list_ids'   => $lists,
            ]
        )->fetchField();

        return (int)$items;
    }

    /**
     * @param string $app
     * @param string $entity_type
     * @param string $entity_id
     * @param array  $dateBounds
     * @param null   $status
     *
     * @return array
     */
    protected function getAppItemsQueryComponents(
        $app = '',
        $entity_type = '',
        $entity_id = '',
        $dateBounds = [],
        $status = null
    ) {
        $query = $this->getQueryComponents();

        if ($app) {
            $query['where']['and'][] = 'pil.app = s:app';
        }

        if ($entity_type) {
            $query['where']['and'][] = 'pil.entity_type = s:type';
        }

        if ($entity_type) {
            $query['where']['and'][] = 'pil.entity_id = i:entity_id';
        }

        if ($status !== null) {
            $query['where']['and'][] = 'i.status = i:status';
        }

        if ($dateBounds) {
            if (isset($dateBounds[1])) {
                $query['where']['and'][] = '((i.status = 0 AND (i.due_date BETWEEN s:date AND s:date2 OR DATE(i.due_datetime) BETWEEN s:date AND s:date2)) OR (i.status > 0 AND DATE(i.complete_datetime) BETWEEN s:date AND s:date2)) /* with due date or completed this day */';
            } elseif (isset($dateBounds[0])) {
                $query['where']['and'][] = '((i.status = 0 AND (i.due_date = s:date OR DATE(i.due_datetime) = s:date)) OR (i.status > 0 AND DATE(i.complete_datetime) = s:date)) /* with due date or completed this day */';
            }
        }

        $query['join']['l'] = 'left join (select i2.name, l2.*
                          from pocketlists_list l2
                                 JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id AND l.archived = 0';
        $query['join']['pil'] = 'join pocketlists_item_link pil ON pil.item_id = i.id';

        $query['group by'][] = 'i.id';
        $query['order by'][] = 'i.status';
        $query['order by'][] = '(i.complete_datetime IS NULL)';
        $query['order by'][] = 'i.complete_datetime DESC';

        return $query;
    }

    /**
     * @param array $query
     * @param int   $limit
     * @param int   $offset
     *
     * @return string
     */
    private function buildSqlComponents(array $query, $limit = 0, $offset = 0)
    {
        $q = sprintf(
            '
            select %s
            from %s
            %s
            %s
            %s
            %s
            %s',
            implode(",\n", $query['select']),
            implode(",\n", $query['from']),
            implode("\n", $query['join']),
            !empty($query['where']['and'])
                ? 'where '.implode(' AND ', $query['where']['and'])
                : '',
            !empty($query['group by'])
                ? 'group by '.implode(",\n", $query['group by'])
                : '',
            !empty($query['order by'])
                ? 'order by '.implode(",\n", $query['order by'])
                : '',
            $limit || $offset ? sprintf('limit %d, %d', $offset, $limit) : ''
        );

        return $q;
    }

    /**
     * @param string $app
     * @param string $entity_type
     * @param string $entity_id
     * @param array  $dateBounds
     *
     * @return array
     */
    public function getAppItems(
        $app = '',
        $entity_type = '',
        $entity_id = '',
        $dateBounds = [],
        $status = null,
        $limit = 0,
        $offset = 0
    ) {
        $lists = [];
        $contact_id = wa()->getUser()->getId();
//        pocketlistsRBAC::filterListAccess($lists, $contact_id);
        $list_sql = 1;//pocketlistsRBAC::filterListAccess($lists);

        $query = $this->getAppItemsQueryComponents($app, $entity_type, $entity_id, $dateBounds, $status);

        $q = $this->buildSqlComponents($query, $limit, $offset);

        $items = $this->query(
            $q,
            [
                'contact_id'      => $contact_id,
                'list_ids'        => $lists,
                'user_contact_id' => wa()->getUser()->getId(),
                'app'             => $app,
                'type'            => $entity_type,
                'entity_id'       => $entity_id,
                'date'            => isset($dateBounds[0]) ? $dateBounds[0] : '',
                'date2'           => isset($dateBounds[1]) ? $dateBounds[1] : '',
                'status'          => $status,
            ]
        )->fetchAll();

        return $items;
    }

    /**
     * @param string $app
     * @param string $entity_type
     * @param string $entity_id
     * @param array  $dateBounds
     *
     * @return int
     */
    public function countAppItems($app = '', $entity_type = '', $entity_id = '', $dateBounds = [], $status = null)
    {
        $lists = [];
        $contact_id = wa()->getUser()->getId();
//        pocketlistsRBAC::filterListAccess($lists, $contact_id);
        $list_sql = 1;//pocketlistsRBAC::filterListAccess($lists);

        $query = $this->getAppItemsQueryComponents($app, $entity_type, $entity_id, $dateBounds, $status);

        $query['select'] = ['count(i.id) count'];
        $query['group by'] = $query['order by'] = [];

        $q = $this->buildSqlComponents($query);

        $items = $this->query(
            $q,
            [
                'contact_id'      => $contact_id,
                'list_ids'        => $lists,
                'user_contact_id' => wa()->getUser()->getId(),
                'app'             => $app,
                'type'            => $entity_type,
                'entity_id'       => $entity_id,
                'date'            => isset($dateBounds[0]) ? $dateBounds[0] : '',
                'date2'           => isset($dateBounds[1]) ? $dateBounds[1] : '',
                'status'          => $status,
            ]
        )->fetchField('count');

        return (int)$items;
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
                $when = "(i.due_date <= '".$today."')";
                break;

            case pocketlistsUserSettings::DAILY_RECAP_FOR_TODAY_AND_TOMORROW:
                $when = "(i.due_date <= '".$tomorrow."')";
                break;

            case pocketlistsUserSettings::DAILY_RECAP_FOR_NEXT_7_DAYS:
                $when = "(i.due_date <= '".$seven_days."')";
                break;
        }

        $lists = pocketlistsRBAC::getAccessListForContact($contact_id);

        $sqlParts = $this->getTodoSqlComponents($contact_id, [], $lists);

        $sqlParts['and'][] = $when;
        $sqlParts['and'][] =

        $or_sql = implode("\n OR ", $sqlParts['or']);
        $and_sql = implode("\n AND ", $sqlParts['and']);
        $join_sql = implode("\n LEFT JOIN ", $sqlParts['join']);
        $select_sql = implode(",\n", $sqlParts['select']);

        // todo: move to this
        $q = $this->buildSqlComponents($sqlParts);

        $q = "SELECT
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
            $q,
            [
                'contact_id' => $contact_id,
                'list_ids'   => $lists,
            ]
        )->fetchAll();

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

    /**
     * @param $user_last_activity
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
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

    /**
     * @param $id
     *
     * @return array|bool
     */
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

        $items = $this->query(
            $sql,
            ['contact_id' => pl2()->getUser()->getContact()->getId(), 'ids' => $value]
        )->fetchAll('id');

        return $all ? $items : reset($items);
    }
}
