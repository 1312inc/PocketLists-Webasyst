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
     * @deprecated
     * Get only active lists and its items with calculated priority that are accessible for current user
     *
     * @return array
     */
    public function getLists($check_access = true, $pocket_id = 0)
    {
        $lists = $this->getAllActiveLists($check_access, $pocket_id);
//        $lists = $this->calculatePriority($lists);

        return $lists;
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
        $accessed_lists = "";
        $available_lists = [];

        if ($check_access) {
            $available_lists = pocketlistsRBAC::getAccessListForContact();
            $accessed_lists = $available_lists ? " AND l.id IN (i:list_ids)" : " AND l.id IS NULL";
        }

        $pocketSql = '';
        if ($pocket_id) {
            $pocketSql = ' AND l.pocket_id = i:pocket_id';
        }

        $sql = "SELECT i.*,
                       l.*,
                       count(i2.id)                                   'items_count',
                       greatest(i.priority, max(i2.priority))         'max_priority',
                       greatest(i.due_date, max(i2.due_date))         'min_due_date',
                       greatest(i.due_datetime, max(i2.due_datetime)) 'min_due_datetime'
                FROM pocketlists_list l
                       JOIN pocketlists_item i ON i.key_list_id = l.id
                       left join pocketlists_item i2 ON i2.status = 0 and i2.list_id = l.id
                WHERE 1 
                {$accessed_lists}
                {$pocketSql}
                GROUP BY l.id
                ORDER BY l.sort, l.id DESC";

        $lists_data = $this->query(
            $sql,
            [
                'list_ids'  => $available_lists,
                'pocket_id' => $pocket_id,
            ]
        )->fetchAll();

//        $lists = self::generateModels($lists_data);

        return $lists_data ?: [];
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
        return (int) $this->query("SELECT id FROM {$this->table} ORDER BY id DESC")->fetchField('id');
    }


    /**
     * @return null|pocketlistsItemModel
     * @throws waDbException
     */
    public function getItem()
    {
        if ($this->item === null) {
            if (!$this->isNewRecord) {
                $this->item = pocketlistsItemModel::model()->findByPk($this->key_item_id);
            }
        }

        return $this->item;
    }
}
