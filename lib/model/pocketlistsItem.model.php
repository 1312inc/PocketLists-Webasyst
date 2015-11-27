<?php

class pocketlistsItemModel extends waModel
{
    protected $table = 'pocketlists_item';


    public function getCompleted()
    {
        $sql = "SELECT
                  i.id id,
                  i.parent_id parent_id,
                  i.has_children has_children,
                  i.name name,
                  i.note note,
                  i.status status,
                  i.priority priority,
                  i.contact_id contact_id,
                  i.due_date due_date,
                  i.due_datetime due_datetime,
                  i.complete_datetime complete_datetime,
                  i.complete_contact_id complete_contact_id,
                  l.id list_id,
                  l.name list_name,
                  p.id pocket_id,
                  p.name pocket_name
                FROM pocketlists_item i
                JOIN pocketlists_list l ON l.id = i.list_id
                JOIN pocketlists_pocket p ON p.id = l.pocket_id
                WHERE i.status > 0
                ORDER BY i.complete_datetime DESC";

        $items = $this->query($sql)->fetchAll();
        foreach ($items as $id => $item) {
            $items[$id] = $this->updateItem($item);
        }
        return $items;
//        return $this->getTree($items, $tree);
    }

    public function getById($id)
    {
        $items = parent::getById($id);
        if (is_array($id)) {
            foreach($items as $id => $item) {
                $items[$id] = $this->updateItem($item);
            }
        } else {
            $items = $this->updateItem($items);
        }
        return $items;
    }

    public function getAllByList($list_id, $tree = true)
    {
        $sql = "SELECT *
                FROM {$this->table}
                WHERE list_id = i:lid
                ORDER BY parent_id, sort ASC";

        return $this->getItems($sql, $list_id, $tree);
    }

    public function getUndoneByList($list_id, $tree = true)
    {
        $sql = "SELECT *
                FROM {$this->table}
                WHERE list_id = i:lid AND status = 0
                ORDER BY parent_id, sort ASC";

        return $this->getItems($sql, $list_id, $tree);
    }

    public function getDoneByList($list_id, $tree = true)
    {
        $sql = "SELECT *
                FROM {$this->table}
                WHERE list_id = i:lid AND status > 0
                ORDER BY parent_id, sort ASC";

        return $this->getItems($sql, $list_id, $tree);
    }

    public function getArchiveByList($list_id, $tree = true)
    {
        $sql = "SELECT *
                FROM {$this->table}
                WHERE list_id = i:lid AND status < 0
                ORDER BY parent_id, sort ASC";

        return $this->getItems($sql, $list_id, $tree);
    }

    private function getItems($sql, $list_id, $tree)
    {
        $items = $this->query($sql, array('lid' => $list_id))->fetchAll();
        foreach ($items as $id => $item) {
            $items[$id] = $this->updateItem($item);
        }
        return $tree ? $this->getTree($items, $tree) : $items;
    }

    private function getTree($items, $tree)
    {
        $result = array();
        foreach ($items as $id => $item) {
            $result[$item['id']] = $item;
            $result[$item['id']]['childs'] = array();
        }

        foreach ($result as $id => $item) {
            $result[$item['parent_id']]['childs'][$id] =& $result[$id];
        }
        if ($tree === true) {
            $result = isset($result[0]) ? $result[0]['childs'] : array();
        } elseif (is_numeric($tree)) {
            $result = isset($result[$tree]) ? array($tree => $result[$tree]) : array();
        }
        return $result;
    }

    public function move($list_id, $id, $before_id)
    {
        if ($before_id) { // before some item - shift other items
            $sql = "SELECT sort FROM {$this->table} WHERE item_id = i:iid AND list_id = i:lid";
            $sort = $this->query(
                $sql,
                array(
                    'iid' => $before_id,
                    'lid' => $list_id
                )
            )->fetchField('sort');
            $sql = "UPDATE pocketlists_item SET sort = sort + 1 WHERE list_id = i:lid AND sort >= i:sort";
            $this->exec(
                $sql,
                array(
                    'lid' => $list_id,
                    'sort' => $sort
                )
            );
        } else { // last position
            $sql = "SELECT sort FROM {$this->table} WHERE list_id = i:lid ORDER BY sort DESC LIMIT 0,1";
            $sort = $this->query(
                    $sql,
                    array(
                        'lid' => $list_id
                    )
                )->fetchField('sort') + 1;
        }
        return $this->updateById($id, array('sort' => $sort, 'update_datetime' => date("Y-m-d H:i:s")));
    }

    private function updateItem($item)
    {
        if ($item['contact_id']) {
            $user = new waContact($item['contact_id']);
            $item['username'] = $user->getName();
        }
        if ($item['complete_contact_id']) {
            $complere_user = new waContact($item['complete_contact_id']);
            $item['complete_username'] = $complere_user->getName();
        }

        $date = strtotime($item['due_date']);
        $now = time();

        $item['due_status'] = 0;
        if ($item['due_date'] || $item['due_datetime']) {
            if ($now > ($item['due_datetime'] ? $item['due_datetime'] : $date)) { // overdue
                $item['due_status'] = 3;
            } elseif ($item['due_date'] == date("Y-m-d")) { // today
                $item['due_status'] = 2;
            } elseif ($item['due_date'] == date("Y-m-d", $now + 60 * 60 * 24)) { // tomorrow
                $item['due_status'] = 1;
            }

            $item['priority'] = max($item['due_status'], $item['priority']);
        }

        return $item;
    }
}