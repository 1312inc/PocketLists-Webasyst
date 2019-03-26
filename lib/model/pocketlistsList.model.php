<?php

/**
 * Class pocketlistsListModel
 *
 * @property int    $sort
 * @property int    $pocket_id
 * @property string $type
 * @property string $icon
 * @property int    $archived
 * @property string $hash
 * @property string $color
 * @property string $passcode
 * @property int    $key_item_id
 * @property int    $contact_id
 * @property int    $parent_id
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
 */
class pocketlistsListModel extends kmModelExt
{
    const TYPE_CHECKLIST = 'checklist';
    const TYPE_NOTES     = 'notes';

    protected $table = 'pocketlists_list';

    /**
     * @var pocketlistsItemModel
     */
    protected $item;

//    public function __get($name)
//    {
//        $method = $this->getMethodName($name);
//        if (method_exists($this, $method)) {
//            return $this->$method();
//        }
//
//        if (parent::hasAttribute($name)) {
//            return $this->attributes[$name]; //todo: cast to $this->fields[$name]['type']
//        }
//
//        if ($this->item instanceof pocketlistsItemModel && $this->item->hasAttribute($name)) {
//            return $this->item->$name;
//        }
//
//        if ($this->hasVirtualAttribute($name)) {
//            return $this->virtualAttributes[$name];
//        }
//
//        throw new waDbException('Invalid attribute: '.$name);
//    }

//    public function setAttribute($name, $value)
//    {
//        $method = $this->setMethodName($name);
//        if (method_exists($this, $method)) {
//            $this->$method($value);
//        } elseif (parent::hasAttribute($name)) {
//            $this->attributes[$name] = $value;
//        } elseif ($this->item instanceof pocketlistsItemModel) {
//            $this->item->setAttribute($name, $value);
//        } else {
//            $this->virtualAttributes[$name] = $value; //todo: hm.. sure?
//        }
//    }

//    public function hasAttribute($name)
//    {
//        return parent::hasAttribute($name) || ($this->item && $this->item->hasAttribute($name));
//    }

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
     * @param array|int $pk
     *
     * @return pocketlistsListModel[]|pocketlistsListModel|null
     * @throws waDbException
     */
    public function findByPk($pk)
    {
        $lists = $this->getById($pk);

        $lists = is_array($pk) ? $lists : [$lists];

        $lists = pocketlistsListModel::generateModels($lists, !is_array($pk));

        return $this->extendListData($lists);
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
        $lists = self::generateModels($lists_data);

        return $lists;
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

    public function save($validate = true, $attributes = [], $type = 0)
    {
        if (parent::save($attributes, $attributes, $type)) {
            $data = $this->getAttributes();
            unset($data['id']);
            $data['key_list_id'] = $this->getPk();
            $im = new pocketlistsItemModel();
            if ($inserted_item_id = $im->insert($data, $type)) {
                $this->updateById($this->getPk(), ['key_item_id' => $inserted_item_id]);
            } else {
                $this->deleteById($this->getPk());
            }
        }
    }

    /**
     * @param int   $id
     * @param array $data
     *
     * @return array|bool|mixed|null
     * @throws waDbException
     * @throws waException
     */
    public function update($id, $data)
    {
        $im = new pocketlistsItemModel();
        unset($data['id']);
        $item = $im->getByField('key_list_id', $id);
        if ($im->addCalculatedPriorityDataAndSave($item['id'], array_merge($item, $data)) &&
            $this->updateById($id, $data)
        ) {
            $list = $this->getById($id);

            return $list;
        }

        return false;
    }

    /**
     * @paramint $id
     *
     * @return bool
     * @throws waDbException
     * @throws waException
     */
    public function remove($id)
    {
        $im = new pocketlistsItemModel();
        $items = $im->getAllByList($id);
        $items_list = $im->getByField('key_list_id', $id, true);

        $im->deleteByField('list_id', $id);
        $im->deleteByField('key_list_id', $id);

        $am = new pocketlistsAttachmentModel();
        $am->remove(array_keys($items)); // items attachements
        $items = [];
        foreach ($items_list as $item) {
            $items[] = $item['id'];
        }
        $am->remove($items); // list attachements

        return $this->deleteById($id);
    }

    /**
     * Get only active lists and its items with calculated priority that are accessible for current user
     *
     * @return array
     */
    public function getLists($check_access = true, $pocket_id = 0)
    {
        $lists = $this->getAllActiveLists($check_access, $pocket_id);
        $lists = $this->calculatePriority($lists);

        return $lists;
    }

    /**
     * @param array $lists
     *
     * @return mixed
     */
    public function calculatePriority($lists)
    {
        foreach ($lists as $list) {
            $list['calc_priority'] = max(
                pocketlistsHelper::calcPriorityOnDueDate($list['min_due_date'], $list['min_due_datetime']),
                $list['max_priority']
            );
        }

        return $lists;
    }

    /**
     * Get all lists (including archived) that are accessible for current user
     *
     * @param bool $check_access
     * @param int  $pocket_id
     *
     * @return null|pocketlistsListModel|pocketlistsListModel[]
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
                       (select count(i2.id) from pocketlists_item i2 where i2.status = 0 and i2.list_id = l.id) 'count',
                       MAX(i.priority)                  'max_priority',
                       MIN(i.due_date)                  'min_due_date',
                       MIN(i.due_datetime)              'min_due_datetime'
                FROM pocketlists_list l
                       JOIN pocketlists_item i ON i.key_list_id = l.id
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

        $lists = self::generateModels($lists_data);

        return $lists ?: [];
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
        $r = $this->query("SELECT id FROM {$this->table} ORDER BY id DESC")->fetch();

        return $r['id'];
    }

    /**
     * @param array|int $pk
     *
     * @return pocketlistsListModel|null
     * @throws waDbException
     */
//    public function findByPk($pk)
//    {
//        /** @var pocketlistsListModel $list */
//        $list = parent::findByPk($pk);
//
//        if (!$list) {
//            return null;
//        }
//
//        $list->getItem();
//
//        return $list;
//    }

    protected function generateWithItem($listsData)
    {
        $lists = [];
        $one = false;

        if (!isset($listsData[0])) {
            $listsData = [$listsData];
            $one = true;
        }

        foreach ($listsData as $listsDatum) {
            $list = new static($listsDatum);
            $list->item = new pocketlistsItemModel($listsDatum);
            $lists[] = $list;
        }

        return $one ? reset($lists) : $lists;
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

    /**
     * @param      $items
     * @param bool $edit
     *
     * @return array|bool|mixed
     * @throws waDbException
     * @throws waException
     */
    public function extendListData($lists, $edit = false)
    {
        if (!is_array($lists) && !$lists instanceof pocketlistsListModel) {
            return false;
        }

        $is_array = true;
        if (isset($lists['id']) || $lists instanceof pocketlistsListModel) {
            $is_array = false;
            $lists = [$lists];
        }
        foreach ($lists as &$list) {
            if ($list['contact_id']) {
                $user = new waContact($list['contact_id']);
                $list['contact'] = pocketlistsHelper::getContactData($user);
            }
        }

        return ($is_array || !$lists) ? $lists : reset($lists);
    }
}
