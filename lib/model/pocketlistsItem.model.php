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
                  i.assigned_contact_id assigned_contact_id,
                  l.id list_id,
                  /*l.name list_name,*/
                  p.id pocket_id,
                  p.name pocket_name,
                  p.color pocket_color,
                  IF(uf.contact_id, 1, 0) favorite
                FROM {$this->table} i
                JOIN pocketlists_list l ON l.id = i.list_id
                JOIN pocketlists_pocket p ON p.id = l.pocket_id
                LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id
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

    public function getToDo($contact_id)
    {
        $pockets = pocketlistsHelper::getAccessPocketForContact($contact_id);
        $sql = "SELECT
                  i.id id,
                  i.parent_id parent_id,
                  i.has_children has_children,
                  i.name name,
                  i.note note,
                  i.status status,
                  i.priority priority,
                  i.contact_id contact_id,
                  i.create_datetime create_datetime,
                  i.due_date due_date,
                  i.due_datetime due_datetime,
                  i.complete_datetime complete_datetime,
                  i.complete_contact_id complete_contact_id,
                  i.assigned_contact_id assigned_contact_id,
                  i.key_list_id key_list_id,
                  l.id list_id,
                  l.icon list_icon,
                  /*l.name list_name,*/
                  p.id pocket_id,
                  p.name pocket_name,
                  p.color pocket_color,
                  IF(uf.contact_id, 1, 0) favorite
                FROM {$this->table} i
                JOIN pocketlists_list l ON (l.id = i.list_id  OR l.id = i.key_list_id) AND l.pocket_id IN (i:pocket_ids)
                JOIN pocketlists_pocket p ON p.id = l.pocket_id
                LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id
                WHERE
                  i.contact_id = i:contact_id AND i.calc_priority > 0
                  OR i.assigned_contact_id = i:contact_id
                  OR i.complete_contact_id = i:contact_id
                ORDER by i.priority DESC";

        $items = $this->query($sql, array('pocket_ids' => $pockets, 'contact_id' => $contact_id))->fetchAll();
        foreach ($items as $id => $item) {
            $items[$id] = $this->updateItem($item);
        }
        return $items;
    }

    public function getById($ids)
    {
        if (!is_array($ids)) {
            $ids = array($ids);
        }
//        $items = parent::getById($id);
        $items = $this->query(
            $this->getQuery()."WHERE id IN (i:id)",
            array('contact_id' => wa()->getUser()->getId(), 'id' => $ids)
        )->fetchAll();
//        $items = $this->getItems($this->getQuery(), null, false);
        foreach ($items as $id => $item) {
            $items[$id] = $this->updateItem($item);
        }
//        return $items;
        return count($ids) > 1 ? $items : reset($items);
    }

    public function updateWithCalcPriority($id, $item)
    {
        $us = new pocketlistsUserSettings();
        if ($email_me = $us->emailWhenNewAssignToMe()) {
            $old_item = $this->getById($id);
        }

        $this->updatePriority($item);
        if ($this->updateById($id, $item)) {
            if ($email_me && // settings are set
                $item['assigned_contact_id'] && // assigned to me is set
                $item['assigned_contact_id'] == wa()->getUser()->getId() && // assigned id is mine
                $item['assigned_contact_id'] != $old_item['assigned_contact_id']
            ) { // assigned id is updated
                pocketlistsNotifications::notifyAboutNewAssign($item);
            }
            return true;
        }
    }

    public function completeItem($id, $status)
    {
        if ($status) {
            $data['complete_datetime'] = date("Y-m-d H:i:s");
            $data['complete_contact_id'] = wa()->getUser()->getId();
        } else {
            $data['complete_contact_id'] = null;
        }
    }

    private function getQuery()
    {
        return "SELECT
                  i.*,
                  IF(uf.contact_id, 1, 0) favorite
                FROM {$this->table} i
                LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id ";
    }

    public function getAllByList($list_id, $tree = true)
    {
        $sql = $this->getQuery() . "
                WHERE list_id = i:lid
                ORDER BY parent_id, sort ASC";

        return $this->getItems($sql, $list_id, $tree);
    }

    public function getUndoneByList($list_id, $tree = true)
    {
        $sql = $this->getQuery() . "
                WHERE list_id = i:lid AND status = 0
                ORDER BY parent_id, sort ASC";

        return $this->getItems($sql, $list_id, $tree);
    }

    public function getDoneByList($list_id, $tree = true)
    {
        $sql = $this->getQuery() . "
                WHERE list_id = i:lid AND status > 0
                ORDER BY parent_id, sort ASC";

        return $this->getItems($sql, $list_id, $tree);
    }

    public function getArchiveByList($list_id, $tree = true)
    {
        $sql = $this->getQuery() . "
                WHERE list_id = i:lid AND status < 0
                ORDER BY parent_id, sort ASC";

        return $this->getItems($sql, $list_id, $tree);
    }


    private function getItems($sql, $list_id, $tree)
    {
        $items = $this->query($sql, array('lid' => $list_id, 'contact_id' => wa()->getUser()->getId()))->fetchAll();
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

    /**
     * @deprecated
     */
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
        // todo: bulk update?
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

        $this->updatePriority($item);

        return $item;
    }

    private function updatePriority(&$item)
    {
        $now = time();
        $due_status = 0;
        if (!empty($item['due_date']) || !empty($item['due_datetime'])) {
            if (!empty($item['due_datetime']) && $now > strtotime($item['due_datetime'])) { // overdue datetime
                $due_status = 3;
            } elseif (strtotime(date("Y-m-d")) > strtotime($item['due_date'])) { // overdue date
                $due_status = 3;
            } elseif ($item['due_date'] == date("Y-m-d")) { // today
                $due_status = 2;
            } elseif ($item['due_date'] == date("Y-m-d", $now + 60 * 60 * 24)) { // tomorrow
                $due_status = 1;
            }
        }
        $item['calc_priority'] = max($due_status, $item['priority']);
    }

    public function sortItems($list_id)
    {
        $sql = $this->getQuery()."WHERE
                  i.list_id = i:id
                  AND i.status = 0
                /*GROUP BY i.parent_id, i.id*/
                ORDER BY i.calc_priority DESC, (i.due_date IS NULL), i.due_date ASC, (i.due_datetime IS NULL), i.due_datetime ASC, i.name ASC";
//        $items = $this->getItems($sql, $list_id, false);
        $items = $this->query($sql, array('id' => $list_id, 'contact_id' => wa()->getUser()->getId()))->fetchAll();

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

    public function getAssignedItemsCountAndNames($contact_ids)
    {
        if (!is_array($contact_ids)) {
            $contact_ids = array($contact_ids);
        }
        $q = "SELECT
                assigned_contact_id,
                name item_name
              FROM {$this->table}
              WHERE assigned_contact_id IN (i:contact_id) AND status = 0";
        return $this->query($q, array('contact_id' => $contact_ids))->fetchAll('assigned_contact_id', 2);
    }

    public function getContactLastActivity($contact_ids)
    {
        if (!is_array($contact_ids)) {
            $contact_ids = array($contact_ids);
        }
        // ох что-то я сомневаюсь
        $q = "SELECT
              MAX(t.last_date) last_activity_datetime,
              t.contact_id contact_id
            FROM
              (
                  SELECT
                    i.complete_contact_id contact_id,
                    max(i.complete_datetime) last_date
                  FROM {$this->table} i
                  WHERE
                    i.complete_contact_id IN (i:contact_id)
                  GROUP BY i.complete_contact_id

                  UNION

                  SELECT
                    i.contact_id contact_id,
                    max(i.create_datetime) last_date
                  FROM {$this->table} i
                  WHERE
                    i.contact_id IN (i:contact_id)
                  GROUP BY i.contact_id
              ) t
            GROUP BY t.contact_id ";
        return $this->query($q, array('contact_id' => $contact_ids))->fetchAll('contact_id', 1);
    }

    public function getAssignedOrCompletesByContactItems($contact_id)
    {
        $q = "SELECT *
              FROM {$this->table}
              WHERE assigned_contact_id = i:id OR complete_contact_id = i:id ";
        $items = $this->query($q, array('id' => $contact_id))->fetchAll();
        $results = array(
            0 => array(),
            1 => array()
        );
        foreach ($items as $id => $item) {
            $results[$item['status']][$id] = $this->updateItem($item);
        }
        return $results;
    }

    public function getDailyRecapItems($contact_id, $when)
    {
        $now = time();
        $today = date("Y-m-d");
        $tomorrow = date("Y-m-d", strtotime("+1 day", $now));
        $seven_days = date("Y-m-d", strtotime("+7 days", $now));
        switch ($when) {
            case pocketlistsUserSettings::DAILY_RECAP_FOR_TODAY:
                $when = " AND (due_date = '" . $today . "' OR (due_datetime >= " . strtotime(
                        $today
                    ) . " AND due_datetime < " . strtotime($tomorrow) . "))";
                break;
            case pocketlistsUserSettings::DAILY_RECAP_FOR_TODAY_AND_TOMORROW:
                $when = " AND (due_date = '" . $today . "' OR due_date = '" . $tomorrow . "' OR (due_datetime >= " . strtotime(
                        $today
                    ) . " AND due_datetime < " . (strtotime($tomorrow) + 60 * 60 * 24) . "))";
                break;
            case pocketlistsUserSettings::DAILY_RECAP_FOR_NEXT_7_DAYS:
                $when = " AND (due_date >= '" . $today . "' AND due_date <= '" . $seven_days . "' OR (due_datetime >= " . strtotime(
                        $today
                    ) . " AND due_datetime < " . (strtotime($seven_days) + 60 * 60 * 24) . "))";
                break;
        }
        $q = "SELECT
                *
              FROM {$this->table}
              WHERE
                assigned_contact_id = i:id
                AND status = 0
                {$when}";

        $items = $this->query($q, array('id' => $contact_id))->fetchAll();
        foreach ($items as $id => $item) {
            $items[$id] = $this->updateItem($item);
        }
        return $items;
    }

    public function getAppCountForUser()
    {
        $us = new pocketlistsUserSettings();
        $settings = $us->getAllSettings();

        $now = @waDateTime::parse('Y-m-d H:i:s', waDateTime::date('Y-m-d H:i:s'));
        $today = date("Y-m-d");
        $tomorrow = date("Y-m-d", strtotime("+1 day"));
        $day_after_tomorrow = date("Y-m-d", strtotime("+2 day"));

        $q = "SELECT id FROM {$this->table} WHERE status = 0 AND assigned_contact_id = i:contact_id";

        switch ($settings['app_icon']) {
            case 0: // overdue
                $q .= " AND ((due_date <= '{$today}' AND due_datetime < '{$now}') OR due_date < '{$today}')";
                break;
            case 1: // overdue + today
                $q .= " AND (due_date <= '" . $today . "' OR due_datetime < '" . $tomorrow . "')";
                break;
            case 2: // overdue + today + tomorrow
                $q .= " AND (due_date <= '" . $tomorrow . "' OR due_datetime < '" . $day_after_tomorrow . "')";
                break;
            default:
                return '';
        }
        return $this->query($q, array('contact_id' => wa()->getUser()->getId()))->count();
    }

}