<?php

/**
 * Class pocketlistsListModel
 */
class pocketlistsListModel extends pocketlistsModel
{
    const TYPE_CHECKLIST = 'checklist';
    const TYPE_NOTES     = 'notes';

    /**
     * @var string
     */
    protected $table = 'pocketlists_list';

    /**
     * @var pocketlistsItemModel
     */
    protected $item;

    /**
     * @param array|int $id
     *
     * @return array
     * @throws waDbException
     */
    public function getById($id)
    {
        if (!$id) {
            return [];
        }

        if (!is_array($id)) {
            $id = [$id];
        }

        $lists = $this->query(
            "SELECT
              i.*,
              l.*,
              uf.contact_id favorite
            FROM {$this->table} l
            LEFT JOIN pocketlists_item i ON i.key_list_id = l.id
            LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id
            WHERE 
              l.id IN (i:id)
            ORDER BY l.sort, l.id DESC",
            ['id' => $id, 'contact_id' => wa()->getUser()->getId()]
        )->fetchAll();

        return count($id) === 1 ? reset($lists) : $lists;
    }


    /**
     * @param int|bool $teammate_contact_id
     *
     * @return pocketlistsListModel[]|pocketlistsListModel|null
     */
    public function getTeamLists($teammate_contact_id = false)
    {
        if (!$teammate_contact_id) {
            $teammate_contact_id = wa()->getUser()->getId();
        }
//        $list_ids = pocketlistsRBAC::getAccessListForContact($id);
//        $list_accessed = pocketlistsRBAC::getAccessListForContact();
        $list_accessed = [];
//        $list_ids = array_intersect($list_ids, $list_accessed);
        $list_sql = pocketlistsRBAC::filterListAccess($list_accessed);
        $list_sql2 = pocketlistsRBAC::filterListAccess($list_accessed, $teammate_contact_id);

        $lists_data = $this->query(
            "SELECT
              i.*,
              l.*,
              uf.contact_id favorite,
              SUM(
                (i2.contact_id = i:contact_id OR i2.assigned_contact_id = i:contact_id) 
                AND i2.status = 0 
              ) items_count
            FROM {$this->table} l
            LEFT JOIN pocketlists_item i ON i.key_list_id = l.id
            LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id
            LEFT JOIN pocketlists_item i2 ON i2.list_id = l.id AND (i2.assigned_contact_id = i:contact_id OR i2.contact_id = i:contact_id)            
            WHERE 
              {$list_sql}
              AND {$list_sql2}
            GROUP BY l.id
            ORDER BY items_count DESC, l.sort, l.id DESC",
            [
                'list_ids'   => $list_accessed,
                'contact_id' => $teammate_contact_id,
            ]
        )->fetchAll();

//        $lists = $this->generateWithItem($lists_data);
//        $lists = self::generateModels($lists_data);

        return $lists_data;
    }

    /**
     * @param array $data
     * @param int   $type
     *
     * @return bool
     * @throws waDbException
     */
    public function add($data, $type = 0)
    {
        if ($inserted_list_id = $this->insert($data, $type)) {
            $data['key_list_id'] = $inserted_list_id;
            $im = new pocketlistsItemModel();
            if ($inserted_item_id = $im->insert($data, $type)) {
                $data['id'] = $inserted_list_id;
                $this->updateById($data['id'], ['key_item_id' => $inserted_item_id]);
            } else {
                $this->deleteById($inserted_list_id);
            }
        }

        return $inserted_list_id ? $data : false;
    }

    /**
     * @return array
     * @deprecated
     * Get only active lists and its items with calculated priority that are accessible for current user
     *
     */
    public function getLists($check_access = true, $pocket_id = 0)
    {
        $lists = $this->getAllActiveLists($check_access, $pocket_id);

//        $lists = $this->calculatePriority($lists);

        return $lists;
    }


    /**
     * @return array
     */
    public function getQueryComponents()
    {
        return [
            'select'   => [
                'i.*',
                'l.*',
                "greatest(i.priority, max(i2.priority)) 'max_priority'",
                "greatest(i.due_date, max(i2.due_date)) 'min_due_date'",
                "greatest(i.due_datetime, max(i2.due_datetime)) 'min_due_datetime'",
            ],
            'from'     => ['l' => "{$this->table} l"],
            'join'     => [
                'join pocketlists_item i ON i.key_list_id = l.id',
                'left join pocketlists_item i2 ON i2.status = 0 and i2.list_id = l.id',
            ],
            'where'    => [
                'and' => [1],
                'or'  => [],
            ],
            'group by' => ['l.id', 'i.id'],
            'order by' => ['l.sort', 'l.id DESC'],
        ];
    }


    /**
     * @param bool $check_access
     * @param int  $pocket_id
     *
     * @return array
     *
     * @throws waDbException
     * @throws waException
     */
    public function getAllListsSqlComponents($pocket_id, $available_lists = false)
    {
        $sqlComponents = $this->getQueryComponents();
        if ($pocket_id) {
            $sqlComponents['where']['and'][] = 'l.pocket_id = i:pocket_id';
        }

        if ($available_lists) {
            $sqlComponents['where']['and'][] = $available_lists ? 'l.id IN (i:list_ids)' : 'l.id IS NULL';
        }

        return $sqlComponents;

    }


    /**
     * @param bool $check_access
     * @param int  $pocket_id
     *
     * @return array
     *
     * @throws waDbException
     * @throws waException
     */
    public function getAllLists($check_access = true, $pocket_id = 0)
    {
        $available_lists = false;
        if ($check_access) {
            $available_lists = pocketlistsRBAC::getAccessListForContact();
        }

        $sql = $this->buildSqlComponents(
            $this->getAllListsSqlComponents($pocket_id, $available_lists)
        );

        $lists_data = $this->query(
            $sql,
            [
                'list_ids'  => $available_lists,
                'pocket_id' => $pocket_id,
            ]
        )->fetchAll('id');

        $this->fillWithItemCount($lists_data, $available_lists);

        return $lists_data ?: [];
    }

    protected function fillWithItemCount(&$lists_data, $available_lists)
    {
        $itemsCount = pl2()->getModel(pocketlistsItem::class)
            ->countListItems($available_lists, pocketlistsItem::STATUS_UNDONE);

        foreach ($itemsCount as $list_id => $itemCount) {
            if (!isset($lists_data[$list_id])) {
                continue;
            }

            $lists_data[$list_id]['itemCount'] = array_combine(
                array_column($itemCount, 'priority'),
                array_column($itemCount, 'count')
            );
        }
    }

    /**
     * Get only archived lists and its items that are accessible for current user
     *
     * @return array
     */
    public function getArchivedLists($check_access = true, $pocket_id = 0)
    {
        $lists = $this->getAllLists($check_access, $pocket_id);

        return $this->filterArchive($lists, true);
    }

    /**
     * Get only active lists and its items that are accessible for current user
     *
     * @return array
     */
    public function getAllActiveLists($check_access = true, $pocket_id = 0)
    {
        $lists = $this->getAllLists($check_access, $pocket_id);

        return $lists ? $this->filterArchive($lists) : [];
    }

    /**
     * @param pocketlistsListModel|pocketlistsListModel[] $lists
     * @param bool                                        $archive
     *
     * @return array
     */
    public function filterArchive($lists, $archive = false)
    {
        if (empty($lists)) {
            return [];
        }

        $is_array = isset($lists[0]);
        if (!$is_array && isset($lists['id'])) {
            $lists = [$lists['id'] => $lists];
        }

        /**
         * @var int                  $id
         * @var pocketlistsListModel $list
         */
        foreach ($lists as $id => $list) {
            if (!isset($list['archived'])) {
                unset($lists[$id]);
            }

            if (!$archive && (int)$list['archived'] > 0) {
                unset($lists[$id]);
            }
            if ($archive && (int)$list['archived'] === 0) {
                unset($lists[$id]);
            }
        }

        return $lists;
    }

    /**
     * @param array $contact_ids
     *
     * @return array
     */
    public function getLastActivitiesList($contact_ids = [])
    {
        $by_contact = "";
        if ($contact_ids && !is_array($contact_ids)) {
            $contact_ids = [$contact_ids];
            $by_contact = " WHERE t.contact_id IN (i:contact_id)";
        }

        // ох что-то я сомневаюсь
        $q = "SELECT
                t.last_date last_date,
                t.contact_id contact_id,
                t.list_id list_id
              FROM (
                  SELECT
                    i.complete_contact_id contact_id,
                    MAX(i.complete_datetime) last_date,
                    i.list_id
                  FROM pocketlists_item i
                  GROUP BY i.list_id
    
                  UNION
    
                  SELECT
                    i.contact_id contact_id,
                    MAX(i.create_datetime) last_date,
                    i.list_id
                  FROM pocketlists_item i
                  GROUP BY i.list_id
                  
                  UNION
    
                  SELECT
                    c.contact_id contact_id,
                    MAX(c.create_datetime) last_date,
                    i.list_id
                  FROM pocketlists_comment c
                  JOIN pocketlists_item i ON c.item_id = i.id
                  GROUP BY i.list_id
              ) t
              {$by_contact}
              GROUP BY list_id
              HAVING list_id IS NOT NULL
              ORDER BY last_date desc";

        return $this->query(
            $q,
            ['contact_id' => $contact_ids]
        )->fetchAll('list_id', 1);
    }

    /**
     * @return mixed
     */
    public function getLastListId()
    {
        return (int)$this->query("SELECT id FROM {$this->table} ORDER BY id DESC")->fetchField('id');
    }

    /**
     * @param string $term
     * @param int    $found
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    public function getByTerm($term, &$found = 0)
    {
        $available_lists = pocketlistsRBAC::getAccessListForContact();
        $accessed_lists = $available_lists ? 'l.id IN (i:list_ids)' : 'l.id IS NULL';

        $sql = "SELECT SQL_CALC_FOUND_ROWS
                       i.*,
                       l.*,
                       count(i2.id)                                   'items_count',
                       max(i2.calc_priority)                          'item_max_priority',
                       sum(if(i2.calc_priority > 0, 1, 0))            'item_count_priority',
                       greatest(i.priority, max(i2.priority))         'max_priority',
                       greatest(i.due_date, max(i2.due_date))         'min_due_date',
                       greatest(i.due_datetime, max(i2.due_datetime)) 'min_due_datetime'
                FROM pocketlists_list l
                   JOIN pocketlists_item i ON i.key_list_id = l.id
                   left join pocketlists_item i2 ON i2.status = 0 and i2.list_id = l.id
                WHERE 
                {$accessed_lists}
                and lower(concat(ifnull(i.name,''),'|',ifnull(i.note,''))) like s:term
                GROUP BY l.id
                ORDER BY l.sort, l.id DESC
                limit 0, 100";

        $lists_data = $this->query(
            $sql,
            [
                'list_ids' => $available_lists,
                'term'     => mb_strtolower('%'.$term.'%'),
            ]
        )->fetchAll('id');

        $found = (int)$this->query('SELECT FOUND_ROWS()')->fetchField();

        $this->fillWithItemCount($lists_data, $available_lists);

        return $lists_data ?: [];
    }
}
