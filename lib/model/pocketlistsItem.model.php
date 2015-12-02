<?php

class pocketlistsItemModel extends waModel
{
    protected $table = 'pocketlists_item';


    public function getCompleted($contact_id = false, $date_range = false)
    {
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
                  p.name pocket_name,
                  p.color pocket_color
                FROM pocketlists_item i
                JOIN pocketlists_list l ON l.id = i.list_id
                JOIN pocketlists_pocket p ON p.id = l.pocket_id
                WHERE
                  i.status > 0
                  {$by_user}
                  {$by_date_range}
                ORDER BY i.complete_datetime DESC";

        $items = $this->query(
            $sql,
            array(
                'contact_id' => wa()->getUser()->getId(),
                'date_after' => !empty($date_range['after']) ? $date_range['after'] : '',
                'date_before' => !empty($date_range['before']) ? $date_range['before'] : '',
            )
        )->fetchAll();
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
            foreach ($items as $id => $item) {
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
            $item['userpic'] = $user->getPhoto('20');
        }
        if ($item['assigned_contact_id']) {
            $user = new waContact($item['assigned_contact_id']);
            $item['assigned_username'] = $user->getName();
            $item['assigned_userpic'] = $user->getPhoto('20');
        }
        if ($item['complete_contact_id']) {
            $user = new waContact($item['complete_contact_id']);
            $item['complete_username'] = $user->getName();
            $item['complete_userpic'] = $user->getPhoto('20');
        }

        $date = strtotime($item['due_date']);
        $now = time();

        $item['due_status'] = 0;
        if ($item['due_date'] || $item['due_datetime']) {
            if ($item['due_datetime'] && $now > $item['due_datetime']) { // overdue datetime
                $item['due_status'] = 3;
            } elseif (strtotime(date("Y-m-d")) > $date) { // overdue date
                $item['due_status'] = 3;
            } elseif ($item['due_date'] == date("Y-m-d")) { // today
                $item['due_status'] = 2;
            } elseif ($item['due_date'] == date("Y-m-d", $now + 60 * 60 * 24)) { // tomorrow
                $item['due_status'] = 1;
            }

            $item['calc_priority'] = max($item['due_status'], $item['priority']);
        }

        return $item;
    }


    public function sortItems($list_id)
    {
        $sql = "SELECT *
                FROM pocketlists_item i
                WHERE
                  i.list_id = i:id
                  AND i.status = 0
                -- GROUP BY i.parent_id, i.id
                ORDER BY i.priority DESC, i.due_date DESC, i.name ASC";
        $items = $this->query($sql, array('id' => $list_id))->fetchAll();

        $sort = 0;
        foreach ($items as $item) {
            $this->updateById(
                $item['id'],
                array(
                    'update_datetime' => date("Y-m-d H:i:s"),
                    'sort' => $sort++
                )
            );
        }
        return $this->getTree($items, true);
    }
}