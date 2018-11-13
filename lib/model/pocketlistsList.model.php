<?php

/**
 * Class pocketlistsListModel
 *
 * @property int    $sort
 * @property string $type
 * @property string $icon
 * @property int    $archived
 * @property string $hash
 * @property string $color
 * @property string $passcode
 * @property int    $key_item_id
 */
class pocketlistsListModel extends kmModelExt
{
    const TYPE_CHECKLIST = 'checklist';
    const TYPE_NOTES = 'notes';

    protected $table = 'pocketlists_list';

    // todo: save lists
    // private lists = [];

    /**
     * @param array|int $id
     *
     * @return array|mixed|null
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
     * @param int|bool $id
     *
     * @return array
     */
    public function getTeamLists($id = false)
    {
        if (!$id) {
            $id = wa()->getUser()->getId();
        }
//        $list_ids = pocketlistsRBAC::getAccessListForContact($id);
//        $list_accessed = pocketlistsRBAC::getAccessListForContact();
        $list_accessed = [];
//        $list_ids = array_intersect($list_ids, $list_accessed);
        $list_sql = pocketlistsRBAC::filterListAccess($list_accessed);
        $list_sql2 = pocketlistsRBAC::filterListAccess($list_accessed, $id);

        $lists = $this->query(
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
                'contact_id' => $id,
            ]
        )->fetchAll();

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
        if ($im->addCalculatedPriorityData($item['id'], array_merge($item, $data)) &&
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
    public function getLists($check_access = true)
    {
        $lists = $this->getAllActiveLists($check_access);
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
        foreach ($lists as $id => &$list) {
            $lists[$id]['calc_priority'] = max(
                pocketlistsHelper::calcPriorityOnDueDate($list['min_due_date'], $list['min_due_datetime']),
                $list['max_priority']
            );
        }

        return $lists;
    }

    /**
     * Get all lists (including archived) that are accessible for current user
     *
     * @return array
     */
    public function getAllLists($check_access = true)
    {
        $accessed_lists = "";
        $available_lists = [];

        if ($check_access) {
            $available_lists = pocketlistsRBAC::getAccessListForContact();
            $accessed_lists = $available_lists ? " WHERE l.id IN (i:list_ids)" : " WHERE l.id IS NULL";
        }

        $sql = "SELECT
                  i2.*,
                  l.*,
                  SUM(IF(i.list_id IS NULL, 0, 1)) 'count',
                  MAX(i.priority) 'max_priority',
                  MIN(i.due_date) 'min_due_date',
                  MIN(i.due_datetime) 'min_due_datetime'
                FROM pocketlists_list l
                LEFT JOIN pocketlists_item i ON i.list_id = l.id AND i.status = 0
                LEFT JOIN pocketlists_item i2 ON i2.key_list_id = l.id
                {$accessed_lists}
                GROUP BY l.id
                ORDER BY l.sort, l.id DESC";

        $lists = $this->query(
            $sql,
            [
                'list_ids' => $available_lists,
            ]
        )->fetchAll();

        return $lists;
    }

    /**
     * Get only archived lists and its items that are accessible for current user
     *
     * @return array
     */
    public function getArchivedLists($check_access = true)
    {
        $lists = $this->getAllLists($check_access);

        return $this->filterArchive($lists, true);
    }

    /**
     * Get only active lists and its items that are accessible for current user
     *
     * @return array
     */
    public function getAllActiveLists($check_access = true)
    {
        $lists = $this->getAllLists($check_access);

        return $this->filterArchive($lists);
    }

    /**
     * @param      $lists
     * @param bool $archive
     *
     * @return array
     */
    public function filterArchive($lists, $archive = false)
    {
        $is_array = !isset($lists['id']);
        if (!$is_array) {
            $lists = [$lists['id'] => $lists];
        }
        foreach ($lists as $id => $list) {
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
}
