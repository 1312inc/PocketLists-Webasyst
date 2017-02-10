<?php

class pocketlistsListModel extends waModel
{
    protected $table = 'pocketlists_list';

    // todo: save lists
    // private lists = [];

    public function getById($id)
    {
        if (!$id) {
            return array();
        }
        if (!is_array($id)) {
            $id = array($id);
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
            array('id' => $id, 'contact_id' => wa()->getUser()->getId())
        )->fetchAll();

        return count($id) === 1 ? reset($lists) : $lists;
    }

    public function getTeamLists($id = false)
    {
        if (!$id) {
            $id = wa()->getUser()->getId();
        }
//        $list_ids = pocketlistsRBAC::getAccessListForContact($id);
        $list_accessed = pocketlistsRBAC::getAccessListForContact();
//        $list_ids = array_intersect($list_ids, $list_accessed);
        $list_sql = pocketlistsRBAC::filterListAccess($list_accessed, $id);

        $lists = $this->query(
            "SELECT
              i.*,
              l.*,
              uf.contact_id favorite,
              SUM(i2.contact_id = i:contact_id AND i2.status = 0) created_items_count,
              SUM(i2.assigned_contact_id = i:contact_id AND i2.status = 0) assigned_items_count
            FROM {$this->table} l
            LEFT JOIN pocketlists_item i ON i.key_list_id = l.id
            LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id
            LEFT JOIN pocketlists_item i2 ON i2.list_id = l.id AND (i2.assigned_contact_id = i:contact_id OR i2.contact_id = i:contact_id)            
            WHERE 
              {$list_sql}
            GROUP BY l.id
            ORDER BY assigned_items_count DESC, l.sort, l.id DESC",
            array(
                'list_ids'   => $list_accessed,
                'contact_id' => $id,
            )
        )->fetchAll();

        return $lists;
    }

    public function add($data, $type = 0)
    {
        if ($inserted_list_id = $this->insert($data, $type)) {
            $data['key_list_id'] = $inserted_list_id;
            $im = new pocketlistsItemModel();
            if ($inserted_item_id = $im->insert($data, $type)) {
                $data['id'] = $inserted_list_id;
                $this->updateById($data['id'], array('key_item_id' => $inserted_item_id));
            } else {
                $this->deleteById($inserted_list_id);
            }
        }
        return $inserted_list_id ? $data : false;
    }

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

    public function delete($id)
    {
        $im = new pocketlistsItemModel();
        $items = $im->getAllByList($id);
        $items_list = $im->getByField('key_list_id', $id, true);

        $im->deleteByField('list_id', $id);
        $im->deleteByField('key_list_id', $id);

        $am = new pocketlistsAttachmentModel();
        $am->delete(array_keys($items)); // items attachements
        $items = array();
        foreach ($items_list as $item) {
            $items[] = $item['id'];
        }
        $am->delete($items); // list attachements

        return $this->deleteById($id);
    }

    /**
     * Get only active lists and its items with calculated priority that are accessible for current user
     * @return array
     */
    public function getLists($check_access = true)
    {
        $lists = $this->getAllActiveLists($check_access);
        $lists = $this->calculatePriority($lists);
        return $lists;
    }

    public function calculatePriority($lists)
    {
        foreach ($lists as $id => &$list) {
            $lists[$id]['calc_priority'] = max(pocketlistsHelper::calcPriorityOnDueDate($list['min_due_date'], $list['min_due_datetime']), $list['max_priority']);
        }
        return $lists;
    }

    /**
     * Get all lists (including archived) that are accessible for current user
     * @return array
     */
    public function getAllLists($check_access = true)
    {
        $accessed_lists = "";
        $available_lists = array();

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
            array(
                'list_ids' => $available_lists
            )
        )->fetchAll();
        return $lists;
    }

    /**
     * Get only archived lists and its items that are accessible for current user
     * @return array
     */
    public function getArchivedLists($check_access = true)
    {
        $lists = $this->getAllLists($check_access);
        return $this->filterArchive($lists, true);
    }

    /**
     * Get only active lists and its items that are accessible for current user
     * @return array
     */
    public function getAllActiveLists($check_access = true)
    {
        $lists = $this->getAllLists($check_access);
        return $this->filterArchive($lists);
    }

    public function filterArchive($lists, $archive = false)
    {
        $is_array = !isset($lists['id']);
        if (!$is_array) {
            $lists = array($lists['id'] => $lists);
        }
        foreach ($lists as $id => $list) {
            if (!$archive && (int) $list['archived'] > 0) {
                unset($lists[$id]);
            }
            if ($archive && (int) $list['archived'] === 0) {
                unset($lists[$id]);
            }
        }
        return $lists;
    }
}
