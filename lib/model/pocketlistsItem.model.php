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
     * @param int  $pocket_id
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
        $pocket_id = 0,
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

        $by_pocket = '';
        if ($pocket_id) {
            $by_pocket = 'join pocketlists_list l2 on i.list_id = l2.id join pocketlists_pocket p2 on (l2.pocket_id = p2.id and p2.id = i:pocket_id)';
        }

        $lists = pocketlistsRBAC::getAccessListForContact();
        $list_sql = $lists ? "AND (l.id IN (i:list_ids) OR (l.id IS NULL AND i.contact_id = i:contact_id))" : "AND (l.id IS NULL AND i.contact_id = i:contact_id)";
        $sql = $this->getQuery().
            "LEFT JOIN (select i2.name, l2.*
                          from pocketlists_list l2
                                 JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id
                 {$by_pocket}
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
                'pocket_id'   => $pocket_id,
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

        return $items ?: [];
    }

    /**
     * @param       $contact_id
     * @param array $dateBounds
     * @param array $calc_priority
     * @param null  $status
     * @param int   $offset
     * @param int   $limit
     *
     * @return array
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
            $sqlParts['where']['and'][] = 'i.status = '.$status;
        }

        $sqlParts['group by'] = ['i.id'];
        $sqlParts['order by'] = ['i.status'];
        if ($status == pocketlistsItem::STATUS_UNDONE) {
            $sqlParts['order by'][] = 'i.calc_priority DESC, ifnull(i.due_datetime, i.due_date) ASC';
        }
        $sqlParts['order by'][] = '(i.complete_datetime IS NULL), i.complete_datetime DESC, i.id DESC';

        $sql = $this->buildSqlComponents($sqlParts, $limit, $offset);
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
     * @return bool|array
     * @throws waDbException
     * @throws waException
     */
    public function countTodo($contact_id, $date = [], array $calc_priority = [], $status = null)
    {
        // get to-do items only from accessed pockets
        $lists = pocketlistsRBAC::getAccessListForContact($contact_id);

        $sqlParts = $this->getTodoSqlComponents($contact_id, $date, $lists, $calc_priority);

        $sqlParts['select'] = ['i.calc_priority calc_priority', 'count(i.id) count'];
        if ($status !== null) {
            $sqlParts['where']['and'][] = 'i.status = ' . $status;
        }
        $sqlParts['group by'] = ['i.calc_priority'];

        $sql = $this->buildSqlComponents($sqlParts);
        $itemsCount = $this->query(
            $sql,
            [
                'list_ids' => $lists,
                'contact_id' => $contact_id,
                'date' => $date,
                'calc_priority' => $calc_priority,
            ]
        )->fetchAll();

        return array_combine(
            array_column($itemsCount, 'calc_priority'),
            array_column($itemsCount, 'count')
        );
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
    public function getTodoSqlComponents($contact_id, array $dateBounds, $lists, array $calc_priority = [])
    {
        $sqlParts = $this->getQueryComponents();

        $sqlParts['select'][] = 'l.color list_color';

        $sqlParts['join'] = [
            '',
            'left join pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id',
            'left join (select i2.name, l2.*
                  from pocketlists_list l2
                         JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id',
        ];

        $sqlParts['where']['and'] = [
            sprintf('(%s OR l.id IS NULL)', pocketlistsRBAC::filterListAccess($lists, $contact_id)),
            '(l.id is null OR (l.id is not null and l.archived = 0))',
            // get to-do items only from accмфп essed pockets
        ];

        $sqlParts['where']['or'] = [
            '(i.list_id IS NULL AND i.key_list_id IS NULL AND i.contact_id = i:contact_id) /* My to-dos to self */',
            '(i.key_list_id IS NULL AND i.assigned_contact_id = i:contact_id) /* To-dos assigned to me by other users */',
        ];

        if ($dateBounds) {
            if (isset($dateBounds[1])) {
                $sqlParts['where']['and'][] = '((i.status = 0 AND (i.due_date BETWEEN s:date AND s:date2 OR DATE(i.due_datetime) BETWEEN s:date AND s:date2)) OR (i.status > 0 AND DATE(i.complete_datetime) BETWEEN s:date AND s:date2)) /* with due date or completed this day */';
            } elseif (isset($dateBounds[0])) {
                $sqlParts['where']['and'][] = '((i.status = 0 AND (i.due_date = s:date OR DATE(i.due_datetime) = s:date)) OR (i.status > 0 AND DATE(i.complete_datetime) = s:date)) /* with due date or completed this day */';
            }
        }

        $us = new pocketlistsUserSettings($contact_id);

        switch ($us->myToDosCreatedByMe()) {
            case pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_ME_IN_SHARED_ANY_LIST:
                $sqlParts['where']['or'][] = '(i.list_id IS NOT NULL AND i.key_list_id IS NULL AND i.contact_id = i:contact_id) /* To-dos created by me in shared lists in just any list */';
                break;

            case pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_ME_IN_SHARED_FAVORITE_LISTS:
                $sqlParts['where']['or'][] = '(i.list_id IS NOT NULL AND i.key_list_id IS NULL AND i.contact_id = i:contact_id AND uf2.contact_id = i:contact_id) /* To-dos created BY other users IN shared lists only in lists which I marked as favorite*/';
                break;
        }

        switch ($us->myToDosCreatedByOthers()) {
            case pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_OTHER_IN_SHARED_LISTS_FAVORITE_LISTS:
                $sqlParts['where']['or'][] = '(i.list_id IS NOT NULL AND i.key_list_id IS NULL AND i.contact_id <> i:contact_id AND uf2.contact_id = i:contact_id) /* To-dos created BY other users IN shared lists only in lists which I marked as favorite*/';
                break;

            case pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_OTHER_IN_SHARED_LISTS_GREEN_YELLOW_RED_ALL_LISTS:
                $tomorrow = date('Y-m-d', strtotime('+1 day'));
                $day_after_tomorrow = date('Y-m-d', strtotime('+2 day'));
                $sqlParts['where']['or'][] = sprintf(
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

        if ($us->myToDosCreatedByOthers() == pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_OTHER_IN_SHARED_LISTS_FAVORITE_LISTS
            || $us->myToDosCreatedByMe() == pocketlistsUserSettings::MY_TO_DOS_CREATED_BY_ME_IN_SHARED_FAVORITE_LISTS) {
            $sqlParts['join'][] = 'left join pocketlists_user_favorites uf2 ON uf2.contact_id = i:contact_id AND uf2.item_id = l.key_item_id';

            $sqlParts['select'][] = 'IF(uf2.contact_id, 1, 0) favorite_list';
        }

        $sqlParts['where']['and'][] = '(i.assigned_contact_id = i:contact_id OR i.assigned_contact_id IS NULL)';

        if ($calc_priority) {
            $sqlParts['where']['and'][] = '(i.calc_priority in (i:calc_priority))';
        }

        $sqlParts['where']['and'][] = sprintf('(%s)', implode(' OR ',  $sqlParts['where']['or']));
//        $sqlParts['where']['and'] = array_merge($sqlParts['where']['or'], $sqlParts['where']['and']);
        $sqlParts['where']['or'] = [];

        return $sqlParts;
    }

    /**
     * @param bool     $contact_id
     * @param bool     $date
     * @param bool     $date2
     * @param null|int $status
     *
     * @return array
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
        $sqlParts['select'] = ['i.calc_priority calc_priority', 'count(i.id) count'];
        $sqlParts['order by'] = [];
        $sqlParts['group by'] = ['i.calc_priority'];

        $sql = $this->buildSqlComponents($sqlParts);

        $itemsCount = $this->query(
            $sql,
            [
                'list_ids'   => $lists,
                'date'       => $date,
                'date2'      => $date2,
                'contact_id' => $contact_id,
            ]
        )->fetchAll();

        $itemsCount = array_combine(
            array_column($itemsCount, 'calc_priority'),
            array_column($itemsCount, 'count')
        );

        return $itemsCount;
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

        if ($status == pocketlistsItem::STATUS_UNDONE) {
            $sqlParts['order by'][] = 'i.calc_priority DESC';
        }

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
    public function getQuery()
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
    public function getQueryComponents()
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
        $by_contact = '';
        if ($contact_ids) {
            if (!is_array($contact_ids)) {
                $contact_ids = [$contact_ids];
            }
            $by_contact = ' WHERE t.contact_id IN (i:contact_id)';
        }

        // ох что-то я сомневаюсь
        $q = <<<SQL
SELECT MAX(t.last_date) last_activity_datetime, t.contact_id contact_id
FROM (SELECT i.complete_contact_id contact_id, max(i.complete_datetime) last_date
      FROM pocketlists_item i
      where i.status = 1
      GROUP BY i.complete_contact_id
      UNION
      SELECT i.contact_id contact_id, max(i.create_datetime) last_date
      FROM pocketlists_item i
      where i.status = 0
      GROUP BY i.contact_id
      UNION
      SELECT c.contact_id contact_id, max(c.create_datetime) last_date
      FROM pocketlists_comment c
      GROUP BY c.contact_id) t
{$by_contact}
GROUP BY t.contact_id
SQL;

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

        if ($status == pocketlistsItem::STATUS_UNDONE) {
            array_splice($sqlParts['order by'], 1, 0, ['i.calc_priority DESC']);
        }

        if ($status == pocketlistsItem::STATUS_DONE) {
            array_splice($sqlParts['order by'], 1, 0, ['(i.complete_datetime IS NULL)', 'i.complete_datetime DESC']);
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
        $sqlParts['select'] = ['i.calc_priority calc_priority', 'count(i.id) count'];
        $sqlParts['group by'] = ['i.calc_priority'];

        $q = $this->buildSqlComponents($sqlParts);

        $itemsCount = $this->query(
            $q,
            [
                'contact_id' => $contact_id,
                'list_ids'   => $lists,
            ]
        )->fetchAll();

        $itemsCount = array_combine(
            array_column($itemsCount, 'calc_priority'),
            array_column($itemsCount, 'count')
        );

        return $itemsCount;
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
            if (!empty($dateBounds[1])) {
                $query['where']['and'][] = '((i.status = 0 AND (i.due_date BETWEEN s:date AND s:date2 OR DATE(i.due_datetime) BETWEEN s:date AND s:date2)) OR (i.status > 0 AND DATE(i.complete_datetime) BETWEEN s:date AND s:date2)) /* with due date or completed this day */';
            } elseif (!empty($dateBounds[0])) {
                $query['where']['and'][] = '((i.status = 0 AND (i.due_date = s:date OR DATE(i.due_datetime) = s:date)) OR (i.status > 0 AND DATE(i.complete_datetime) = s:date)) /* with due date or completed this day */';
            }
        }

        $query['join']['l'] = 'left join (select i2.name, l2.*
                          from pocketlists_list l2
                                 JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id AND l.archived = 0';
        $query['join']['pil'] = 'join pocketlists_item_link pil ON pil.item_id = i.id';

        $query['group by'][] = 'i.id';

        $query['order by'][] = 'i.status';

        if ($status == pocketlistsItem::STATUS_UNDONE) {
            $query['order by'][] = 'i.calc_priority DESC';
        }

        $query['order by'][] = '(i.complete_datetime IS NULL)';
        $query['order by'][] = 'i.complete_datetime DESC';

        return $query;
    }

    /**
     * @param string $app
     * @param string $entity_type
     * @param string $entity_id
     * @param array  $dateBounds
     * @param null   $status
     * @param int    $limit
     * @param int    $offset
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
                'date'            => !empty($dateBounds[0]) ? $dateBounds[0] : '',
                'date2'           => !empty($dateBounds[1]) ? $dateBounds[1] : '',
                'status'          => (int)$status,
            ]
        )->fetchAll();

        return $items ?: [];
    }

    /**
     * @param string $app
     * @param string $entity_type
     * @param string $entity_id
     * @param array  $dateBounds
     * @param null   $status
     * @param int    $limit
     * @param int    $offset
     *
     * @return array
     */
    public function getTasksAppItems(
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
                'date'            => !empty($dateBounds[0]) ? $dateBounds[0] : '',
                'date2'           => !empty($dateBounds[1]) ? $dateBounds[1] : '',
                'status'          => (int)$status,
            ]
        )->fetchAll();

        return $items ?: [];
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

        return $items;
    }

    /**
     * @param int|array $listIds
     * @param null      $status
     *
     * @return array
     */
    public function countListItems($listIds, $status = null)
    {
        if (!is_array($listIds)) {
            $listIds = [$listIds];
        }

        $statusSql = '';
        if ($status !== null) {
            $statusSql = ' and i.status = i:status';
        }

        $listSql = 'i.list_id in (i:listIds)';
        if (empty($listIds)) {
            $listSql = '1';
        }

        return $this->query(
            "SELECT 
                    i.calc_priority priority,
                    count(i.id) count,
                    i.list_id
            from {$this->table} i
            where
                {$listSql}  
                {$statusSql}
                AND i.list_id > 0
            GROUP BY i.list_id, i.calc_priority",
            ['status' => $status, 'listIds' => $listIds]
        )->fetchAll('list_id', 2);
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

        $sqlParts['where']['and'][] = $when;
//        $sqlParts['where']['and'][] = 'l.archived = 0';
        $sqlParts['group by'] = ['i.id'];
        $sqlParts['order by'] = ['i.status', '(i.complete_datetime IS NULL), i.complete_datetime DESC'];

        $q = $this->buildSqlComponents($sqlParts);
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

        $itemsArchiveSql = <<<SQL
select count(i.id)
FROM pocketlists_item i
LEFT JOIN pocketlists_list l ON l.id = i.list_id
WHERE 
    {$list_sql}
    AND (
      i.create_datetime > s:user_last_activity 
      OR i.complete_datetime > s:user_last_activity
    )
    and l.archived=0 and i.status=0
SQL;

        $itemsLogbookSql = <<<SQL
select count(i.id)
FROM pocketlists_item i
LEFT JOIN pocketlists_list l ON l.id = i.list_id
WHERE 
    {$list_sql}
    AND (
      i.create_datetime > s:user_last_activity 
      OR i.complete_datetime > s:user_last_activity
    )
    and i.complete_contact_id<>i:user_id and i.status=1
SQL;

        $itemsTeamSql = <<<SQL
select i.assigned_contact_id,
       count(i.id)
FROM pocketlists_item i
FORCE INDEX (assigned_contact_id)
LEFT JOIN pocketlists_list l ON l.id = i.list_id
WHERE 
    {$list_sql}
    AND (
      i.create_datetime > s:user_last_activity 
      OR i.complete_datetime > s:user_last_activity
    )
    and i.assigned_contact_id<>i:user_id and i.status=1
group by i.assigned_contact_id
SQL;

        $params = [
            'user_id' => $user_id,
            'list_ids' => $lists,
            'user_last_activity' => $user_last_activity,
        ];

        $result = [
            'archive' => $this->query($itemsArchiveSql, $params)->fetchField(),
            'logbook' => $this->query($itemsLogbookSql, $params)->fetchField(),
            'team' => $this->query($itemsTeamSql, $params)->fetchAll('assigned_contact_id', 1),
        ];

        return $result;
    }

    /**
     * @param string $app
     *
     * @return array
     */
    public function getCountForApp($app)
    {
        $itemsCount = $this->query(
            "SELECT 
                calc_priority calc_priority,
                count(lid) count
             FROM (
                SELECT l.item_id lid, i.calc_priority calc_priority
                FROM {$this->table} i 
                JOIN pocketlists_item_link l ON i.id = l.item_id AND i.status = 0
                WHERE app = s:app 
                GROUP BY l.item_id) t
                group by calc_priority",
            ['app' => $app]
        )->fetchAll();

        $itemsCount = array_combine(
            array_column($itemsCount, 'calc_priority'),
            array_column($itemsCount, 'count')
        );

        return $itemsCount;
    }

    /**
     * @param string $app
     * @param string $entityType
     * @param array  $entityIds
     * @param int    $status
     *
     * @return array|false
     */
    public function countLinkedItemsByAppAndEntities(
        $app,
        $entityType,
        array $entityIds,
        $status = pocketlistsItem::STATUS_UNDONE
    ) {
        $itemsCount = $this->query(
            "SELECT 
                count(l.item_id) count, 
                i.calc_priority calc_priority, 
                l.entity_id entity_id
             FROM {$this->table} i 
             JOIN pocketlists_item_link l ON i.id = l.item_id AND i.status = i:status
             WHERE l.app = s:app 
                and l.entity_type = s:entity_type
                and l.entity_id in (i:entity_ids)
             GROUP BY l.entity_id, calc_priority",
            [
                'status' => $status,
                'app' => $app,
                'entity_type' => $entityType,
                'entity_ids' => $entityIds,
            ]
        )->fetchAll('entity_id', 2);

        return $itemsCount;
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
        $itemsWithDue = $this->where('status = 0 and due_date is not null or due_datetime is not null')->fetchAll('id');
        foreach ($itemsWithDue as $item) {
            $newCalcPriority = max(
                pocketlistsHelper::calcPriorityOnDueDate($item['due_date'], $item['due_datetime']),
                $item['priority']
            );

            if ($item['calc_priority'] != $newCalcPriority) {
                $this->updateById($item['id'], ['calc_priority' => $newCalcPriority]);
            }
        }
        wa()->getUser()->setSettings(pocketlistsHelper::APP_ID, 'last_updateCalcPriority', time());
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

    /**
     * @param string $term
     * @param int    $found
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    public function getByTerm($term, &$found = 0, $contact_id = 0, $limit = 300)
    {
        $available_lists = pocketlistsRBAC::getAccessListForContact();

        if (!$contact_id) {
            $contact_id = wa()->getUser()->getId();
        }

        $sqlParts = $this->getQueryComponents();

        $sqlParts['join'][] = 'LEFT JOIN (select i2.name, l2.*
                          from pocketlists_list l2
                                 JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id AND l.archived = 0';

        if (count($available_lists)) {
            $sqlParts['where']['and'][] = '(l.id IN (i:list_ids) OR l.id IS NULL) /* ONLY items from accessed pockets or NULL-list items */';
        } else {
            $sqlParts['where']['and'][] = 'l.id IS NULL /* ONLY items from NULL-list items */';
        }

        $sqlParts['where']['and'][] = "lower(concat(ifnull(i.name,''), '|', ifnull(i.note,''))) like s:term";
        $sqlParts['where']['and'][] = 'i.key_list_id is null';

        $sql = $this->buildSqlComponents($sqlParts, $limit);

        $lists_data = $this->query(
            $sql,
            [
                'list_ids'   => $available_lists,
                'term'       => mb_strtolower('%'.$term.'%'),
                'contact_id' => $contact_id,
            ]
        )->fetchAll();

        $found = (int)$this->query('SELECT FOUND_ROWS()')->fetchField();

        return $lists_data ?: [];
    }
}
