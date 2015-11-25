<?php

class pocketlistsItemModel extends waModel
{
    protected $table = 'pocketlists_item';

    public function getById($id)
    {
        $items = parent::getById($id);
        if (is_array($id)) {
            foreach($items as $id => $item) {
                $items[$id] = $this->updateItemPriority($item);
            }
        } else {
            $items = $this->updateItemPriority($items);
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
            $items[$id] = $this->updateItemPriority($item);
            $items[$id]['username'] = wa()->getUser()->getName();
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

    private function updateItemPriority($item)
    {
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