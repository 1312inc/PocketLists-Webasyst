<?php

class pocketlistsItemModel extends waModel
{
    protected $table = 'pocketlists_item';

    public function getCompletedItems($contact_id = false, $date_range = false)
    {
        return $this->getLogbookItems($contact_id, $date_range);
    }

    public function getLogbookItems($contact_id = false, $date_range = false, $completed = false)
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

        $only_completed = '';
        if ($completed) {
            $only_completed = " AND i.status > 0";
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
                  l.icon list_icon,
                  l.color list_color,
                  i2.name list_name,
                  IF(uf.contact_id, 1, 0) favorite
                FROM {$this->table} i
                LEFT JOIN pocketlists_list l ON l.id = i.list_id
                LEFT JOIN pocketlists_item i2 ON i2.key_list_id = i.list_id
                LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id
                WHERE
                  i.key_list_id IS NULL
                  AND (
                    l.id IN (i:list_ids)
                    OR (l.id IS NULL AND i.contact_id = i:contact_id)
                  )
                  {$only_completed}
                  {$by_user}
                  {$by_date_range}
                ORDER BY i.complete_datetime DESC";

        $items = $this->query(
            $sql,
            array(
                'contact_id' => wa()->getUser()->getId(),
                'list_ids' => pocketlistsHelper::getAccessListForContact(),
                'date_after' => !empty($date_range['after']) ? $date_range['after'] : '',
                'date_before' => !empty($date_range['before']) ? $date_range['before'] : '',
            )
        )->fetchAll();

        $activities = $this->getLastActivities();
        $result = array();
        foreach ($activities as $id => $item) {
            if (isset($items[$id])) {
                $result[$id] = $this->updateItem($items[$id]);
                unset($items[$id]);
            }
        }
        foreach ($items as $id => $item) {
            $result[$id] = $this->updateItem($items[$id]);
        }
        return $result;
//        return $this->getTree($items, $tree);
    }

    public function getToDo($contact_id, $date = false)
    {
        // get to-do items only from accessed pockets
        $lists = pocketlistsHelper::getAccessListForContact($contact_id);
        $due_date_or_mine = "AND (i.assigned_contact_id = i:contact_id OR i.assigned_contact_id IS NULL OR i.assigned_contact_id = 0) /* ONLY assigned to me or noone */";
        if ($date) {
            $due_date_or_mine = "AND ((i.status = 0 AND (i.due_date = s:date OR DATE(i.due_datetime) = s:date)) OR (i.status > 0 AND DATE(i.complete_datetime) = s:date)) /* with due date or completed this day */";
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
                  i.create_datetime create_datetime,
                  i.due_date due_date,
                  i.due_datetime due_datetime,
                  i.complete_datetime complete_datetime,
                  i.complete_contact_id complete_contact_id,
                  i.assigned_contact_id assigned_contact_id,
                  i.key_list_id key_list_id,
                  l.id list_id,
                  l.icon list_icon,
                  l.color list_color,
                  i2.name list_name,
                  IF(uf.contact_id, 1, 0) favorite
                FROM {$this->table} i
                LEFT JOIN pocketlists_list l ON (l.id = i.list_id  OR l.id = i.key_list_id)
                LEFT JOIN pocketlists_item i2 ON i2.key_list_id = i.list_id 
                LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id
                WHERE
                (
                  (i.assigned_contact_id = i:contact_id) /* + items assigned to me */
                  OR i.priority > 0 /* + items with priority */
                  OR
                  (
                    /*((i.due_date IS NOT NULL OR i.due_datetime IS NOT NULL) AND i.list_id IS NULL AND i.key_list_id IS NULL AND i.contact_id = 7)
                    OR
                    ((i.due_date IS NOT NULL OR i.due_datetime IS NOT NULL) AND (i.list_id IS NOT NULL OR i.key_list_id IS NOT NULL)) */
                    (i.due_date IS NOT NULL OR i.due_datetime IS NOT NULL) /* + items with due */
                    AND
                    IF(i.list_id IS NULL AND i.key_list_id IS NULL, i.contact_id = i:contact_id, i.contact_id > 0) /* + and if item is from NULL-list check contact_id */
                  )
                  OR (i.complete_contact_id = i:contact_id) /* + items completed by me */
                  OR (i.contact_id = i:contact_id AND i.list_id IS NULL AND i.key_list_id IS NULL) /* + my items from NULL-list */
                )
                AND (l.archived = 0 OR l.archived IS NULL) /* ONLY not archived items */
                AND (l.id IN (i:list_ids) OR l.id IS NULL) /* ONLY items from accessed pockets or NULL-list items */
                {$due_date_or_mine}
                ORDER BY
                  i.status,
                  (i.complete_datetime IS NULL), i.complete_datetime DESC";

        $items = $this->query($sql, array(
            'list_ids' => $lists,
            'contact_id' => $contact_id,
            'date' => $date))->fetchAll();

        $result = array(
            0 => array(),
            1 => array(),
        );
        foreach ($items as $id => $item) {
            $result[$item['status']][$id] = $this->updateItem($item);
        }
        return array(
            0 => $this->getProperSort($result[0]),
            1 => $result[1]
        );
//        return $this->getTree($items, true);
    }

    public function getFavorites($contact_id)
    {
        // get to-do items only from accessed pockets
        $lists = pocketlistsHelper::getAccessListForContact($contact_id);
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
                  l.color list_color,
                  i2.name list_name,
                  IF(uf.contact_id, 1, 0) favorite
                FROM {$this->table} i
                LEFT JOIN pocketlists_list l ON (l.id = i.list_id  OR l.id = i.key_list_id)
                LEFT JOIN pocketlists_item i2 ON i2.key_list_id = i.list_id
                LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id
                WHERE
                uf.item_id IS NOT NULL
                AND (l.archived = 0 OR l.archived IS NULL) /* ONLY not archived items */
                AND (l.id IN (i:list_ids) OR l.id IS NULL) /* ONLY items from accessed pockets or NULL-list items */
                ORDER BY
                  i.status,
                  (i.complete_datetime IS NULL), i.complete_datetime DESC";

        $items = $this->query($sql, array(
            'list_ids' => $lists,
            'contact_id' => $contact_id))->fetchAll();

        $result = array(
            0 => array(),
            1 => array(),
        );
        foreach ($items as $id => $item) {
            $result[$item['status']][$id] = $this->updateItem($item);
        }
        return array(
            0 => $this->getProperSort($result[0]),
            1 => $result[1]
        );
//        return $this->getTree($items, true);
    }

    public function getById($ids, $user_id = false)
    {
        if (!$user_id) {
            $user_id = wa()->getUser()->getId();
        }
        if (!is_array($ids)) {
            $ids = array($ids);
        }
//        $items = parent::getById($id);
        $items = $this->query(
            $this->getQuery()."WHERE i.id IN (i:id)",
            array('contact_id' => $user_id, 'id' => $ids)
        )->fetchAll();
//        $items = $this->getItems($this->getQuery(), null, false);
        foreach ($items as $id => $item) {
            $items[$id] = $this->updateItem($item);
        }
//        return $items;
        return count($ids) > 1 ? $items : reset($items);
    }

    public function updateWithCalcPriority($id, $item, $silent = false)
    {
        $email_to_assigned_contact = false;
        $old_item = array('assigned_contact_id' => false);
        if (!$silent && $item['assigned_contact_id']) {
            $us = new pocketlistsUserSettings($item['assigned_contact_id']);
            $email_to_assigned_contact = $us->emailWhenNewAssignToMe();
            if ($email_to_assigned_contact) {
                $old_item = $this->getById($id);
            }
        }

        $this->updatePriority($item);
        if ($this->updateById($id, $item)) {
            if (!$silent && $email_to_assigned_contact && // settings are set
                $item['assigned_contact_id'] != wa()->getUser()->getId() && // do not email if I assign myself
                $item['assigned_contact_id'] != $old_item['assigned_contact_id']
            ) { // assigned id is updated
                pocketlistsNotifications::notifyAboutNewAssign($item, wa()->getUser()->getName());
            }
            return true;
        }
        return false;
    }

    private function getQuery()
    {
        return "SELECT
                  i.*,
                  IF(uf.contact_id, 1, 0) favorite,
                  pi2.name list_name,
                  pl.sort list_sort,
                  pl.type list_type,
                  pl.icon list_icon,
                  pl.archived list_archived,
                  pl.hash list_hash,
                  pl.color list_color
                FROM {$this->table} i
                LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:contact_id AND uf.item_id = i.id
                LEFT JOIN pocketlists_list pl ON pl.id = i.list_id
                LEFT JOIN pocketlists_item pi2 ON pi2.key_list_id = i.list_id
                ";
    }

    public function getAllByList($list_id, $tree = true)
    {
        $sql = $this->getQuery() . "
                WHERE i.list_id = i:lid
                ORDER BY i.parent_id, i.sort ASC, i.id DESC";

        return $this->getItems($sql, $list_id, $tree);
    }

    public function getUndoneByList($list_id, $tree = true)
    {
        $sql = $this->getQuery() . "
                WHERE i.list_id = i:lid AND i.status = 0
                ORDER BY i.parent_id, i.sort ASC, i.id DESC";

        return $this->getItems($sql, $list_id, $tree);
    }

    public function getDoneByList($list_id, $tree = true)
    {
        $sql = $this->getQuery() . "
                WHERE i.list_id = i:lid AND i.status > 0
                ORDER BY i.complete_datetime DESC, i.parent_id, i.sort ASC, i.id DESC";

        return $this->getItems($sql, $list_id, $tree);
    }

    public function getArchiveByList($list_id, $tree = true)
    {
        $sql = $this->getQuery() . "
                WHERE i.list_id = i:lid AND i.status < 0
                ORDER BY i.parent_id, i.sort ASC, i.id DESC";

        return $this->getItems($sql, $list_id, $tree);
    }

    private function getItems($sql, $list_id, $tree)
    {
        $items = $this->query($sql, array('lid' => $list_id, 'contact_id' => wa()->getUser()->getId()))->fetchAll('id');
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

        $am = new pocketlistsAttachmentModel();
        $item['attachments'] = $am->getByField('item_id', $item['id'], true);

        $this->updateChat($item);

        $this->updatePriority($item);

        return $item;
    }

    private function updatePriority(&$item)
    {
        $item['calc_priority'] = max(
            pocketlistsHelper::calcPriorityOnDueDate($item['due_date'], $item['due_datetime']),
            isset($item['priority']) ? $item['priority'] : 0
        );
    }

    private function updateChat(&$item)
    {
        $cm = new pocketlistsCommentModel();
        $chat = $cm->getByField('item_id', $item['id'], true);
        $item['chat'] = array(
            'current_user' => array(
                'username' => wa()->getUser()->getName(),
                'userpic' => wa()->getUser()->getPhoto(20),
            ),
            'comments' => array()
        );
        foreach ($chat as $comment) {
            $item['chat']['comments'][$comment['id']] = $comment;
            $comment_user = new waContact($comment['contact_id']);
            $item['chat']['comments'][$comment['id']]['my'] = $comment['contact_id'] == wa()->getUser()->getId() ? true : false;
            $item['chat']['comments'][$comment['id']]['username'] = $comment_user->getName();
            $item['chat']['comments'][$comment['id']]['userpic'] = $comment_user->getPhoto('20');
        }
    }

    private function getProperSort($items)
    {
        usort($items, array($this, 'compare_for_proper_sort'));
        return $items;
    }

    private function compare_for_proper_sort($i1, $i2)
    {
        if ($i1['calc_priority'] < $i2['calc_priority']) {
            return 1;
        } elseif ($i1['calc_priority'] > $i2['calc_priority']) {
            return -1;
        } else {
            $date1 = !empty($i1['due_datetime']) ? strtotime($i1['due_datetime']) :
                (!empty($i1['due_date']) ? strtotime($i1['due_date']) : null);
            $date2 = !empty($i2['due_datetime']) ? strtotime($i2['due_datetime']) :
                (!empty($i2['due_date']) ? strtotime($i2['due_date']) : null);
            // check due_date
            if ($date1 && $date2) { // check both dates
                if ($date1 < $date2) {
                    return -1;
                } elseif ($date1 > $date2) {
                    return 1;
                } else {
                    return 0;
                }
            } elseif ($date1 && !$date2) {
                return -1;
            } elseif (!$date1 && $date2) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    public function sortItems($list_id)
    {
        $sql = $this->getQuery()."WHERE
                  i.list_id = i:id
                  AND i.status = 0
                  ORDER BY i.id DESC
                /*GROUP BY i.parent_id, i.id*/";
//        $items = $this->getItems($sql, $list_id, false);
        $items = $this->query($sql, array('id' => $list_id, 'contact_id' => wa()->getUser()->getId()))->fetchAll();

        $items = $this->getProperSort($items);
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
                i.id id,
                i.assigned_contact_id assigned_contact_id,
                i.name name,
                i.priority priority,
                i.due_date due_date,
                i.due_datetime due_datetime
              FROM {$this->table} i
              LEFT JOIN pocketlists_list l ON l.id = i.list_id
              WHERE
                i.assigned_contact_id IN (i:contact_id)
                AND i.status = 0
                AND (l.archived = 0 OR l.archived IS NULL) /*archived is NULL when item is in null-list*/";
        $items = $this->query($q, array('contact_id' => $contact_ids))->fetchAll('id');
        $result = array();
        foreach ($items as $assigned_item_id => $assigned_item) {
            $this->updatePriority($assigned_item);
            $result[$assigned_item['assigned_contact_id']]['item_names'][] = $assigned_item['name'];
            $result[$assigned_item['assigned_contact_id']]['item_max_priority'] = max(
                isset($result[$assigned_item['assigned_contact_id']]['item_max_priority']) ?
                    $result[$assigned_item['assigned_contact_id']]['item_max_priority'] : 0,
                $assigned_item['calc_priority']
            );
        }
        return $result;
    }

    public function getLastActivities($contact_ids = array())
    {
        $by_contact = "";
        if ($contact_ids && !is_array($contact_ids)) {
            $contact_ids = array($contact_ids);
            $by_contact = " WHERE t.contact_id IN (i:contact_id)";
        }

        // ох что-то я сомневаюсь
        $q = "SELECT
              MAX(t.last_date) last_activity_datetime,
              t.contact_id contact_id
            FROM (
                  SELECT
                    i.complete_contact_id contact_id,
                    max(i.complete_datetime) last_date
                  FROM {$this->table} i
                  GROUP BY i.complete_contact_id

                  UNION

                  SELECT
                    i.contact_id contact_id,
                    max(i.create_datetime) last_date
                  FROM {$this->table} i
                  GROUP BY i.contact_id
                  
                  UNION

                  SELECT
                    c.contact_id contact_id,
                    max(c.create_datetime) last_date
                  FROM pocketlists_comment c
                  GROUP BY c.contact_id
              ) t
            {$by_contact}
            GROUP BY t.contact_id";

        return $this->query(
            $q,
            array('contact_id' => $contact_ids)
        )->fetchAll('contact_id', 1);
    }

    public function getAssignedOrCompletesByContactItems($contact_id)
    {
        $lists = pocketlistsHelper::getAccessListForContact();

        $q = "SELECT
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
                  l.color list_color,
                  i2.name list_name,
                  IF(uf.contact_id, 1, 0) favorite
                FROM {$this->table} i
                LEFT JOIN pocketlists_list l ON (l.id = i.list_id  OR l.id = i.key_list_id)
                LEFT JOIN pocketlists_item i2 ON i2.key_list_id = i.list_id
                LEFT JOIN pocketlists_user_favorites uf ON uf.contact_id = i:user_contact_id AND uf.item_id = i.id
                WHERE
                  (
                    i.assigned_contact_id = i:contact_id AND i.status = 0
                    OR i.complete_contact_id = i:contact_id AND i.status > 0
                  )
                  AND (
                    l.archived = 0
                    OR l.archived IS NULL
                  )
                  AND ( /* only accessed pockets or null list */
                    l.id IN (i:list_ids)
                    OR l.id IS NULL
                  )
                ORDER BY
                  i.status,
                  (i.complete_datetime IS NULL), i.complete_datetime DESC";
        $items = $this->query($q, array(
            'contact_id' => $contact_id,
            'list_ids' => $lists,
            'user_contact_id' => wa()->getUser()->getId()))->fetchAll();
        $results = array(
            0 => array(),
            1 => array()
        );
        foreach ($items as $id => $item) {
            $results[$item['status']][$id] = $this->updateItem($item);
        }
        return array(
            0 => $this->getProperSort($results[0]),
            1 => $results[1]
        );
    }

    public function getDailyRecapItems($contact_id, $when)
    {
        $now = time();
        $today = date("Y-m-d");
        $tomorrow = date("Y-m-d", strtotime("+1 day", $now));
        $seven_days = date("Y-m-d", strtotime("+7 days", $now));
        switch ($when) {
            case pocketlistsUserSettings::DAILY_RECAP_FOR_TODAY:
                $when = " AND (i.due_date <= '" . $today . "')";
                break;
            case pocketlistsUserSettings::DAILY_RECAP_FOR_TODAY_AND_TOMORROW:
                $when = " AND (i.due_date <= '" . $tomorrow . "')";
                break;
            case pocketlistsUserSettings::DAILY_RECAP_FOR_NEXT_7_DAYS:
                $when = " AND (i.due_date <= '" . $seven_days . "')";
                break;
        }
        $q = "SELECT
                i.*
              FROM {$this->table} i
              LEFT JOIN pocketlists_list l ON (l.id = i.list_id  OR l.id = i.key_list_id)
              WHERE
                (
                  (i.assigned_contact_id = i:contact_id) /* + items assigned to me */
                  OR i.priority > 0 /* + items with priority */
                  OR
                  (
                    /*((i.due_date IS NOT NULL OR i.due_datetime IS NOT NULL) AND i.list_id IS NULL AND i.key_list_id IS NULL AND i.contact_id = 7)
                    OR
                    ((i.due_date IS NOT NULL OR i.due_datetime IS NOT NULL) AND (i.list_id IS NOT NULL OR i.key_list_id IS NOT NULL)) */
                    (i.due_date IS NOT NULL OR i.due_datetime IS NOT NULL) /* + items with due */
                    AND
                    IF(i.list_id IS NULL AND i.key_list_id IS NULL, i.contact_id = i:contact_id, i.contact_id > 0) /* + and if item is from NULL-list check contact_id */
                  )
                  OR (i.contact_id = i:contact_id AND i.list_id IS NULL AND i.key_list_id IS NULL) /* + my items from NULL-list */
                )
                AND i.status = 0 /* ONLY not completed items */
                AND (l.archived = 0 OR l.archived IS NULL) /* ONLY not archived items */
                AND (i.assigned_contact_id = i:contact_id OR i.assigned_contact_id IS NULL OR i.assigned_contact_id = 0) /* ONLY assigned to me or noone */
                AND (
                  l.id IN (i:list_ids)
                  OR l.id IS NULL
                )
                {$when}";

        $items = $this->query($q, array(
            'contact_id' => $contact_id,
            'list_ids' => pocketlistsHelper::getAccessListForContact($contact_id)
        ))->fetchAll();
        foreach ($items as $id => $item) {
            $items[$id] = $this->updateItem($item);
        }
        return $items;
    }

    public function getAppCountForUser()
    {
        $us = new pocketlistsUserSettings();
        $icon = $us->appIcon();

        $pocket_rights = "";
        $lists = array();
        // if user is admin - show all completed items
        // else only items user has access and null list items
        if (!pocketlistsHelper::isAdmin()) {
            $lists = pocketlistsHelper::getAccessListForContact();
            // only accessed pockets or null list items which are created or completed by user
//            $pocket_rights = "AND (
//                    p.id IN (i:pocket_ids)
//                    OR (p.id IS NULL AND (i.contact_id = i:contact_id OR i.complete_contact_id = i:contact_id)
//                  )";
            // only accessed pockets or null list items
            $pocket_rights = "AND (
                    l.id IN (i:list_ids)
                    OR l.id IS NULL
                  )";
        }

        $now = @waDateTime::parse('Y-m-d H:i:s', waDateTime::date('Y-m-d H:i:s'));
        $today = date("Y-m-d");
        $tomorrow = date("Y-m-d", strtotime("+1 day"));
        $day_after_tomorrow = date("Y-m-d", strtotime("+2 day"));

        switch ($icon) {
            case pocketlistsUserSettings::ICON_OVERDUE: // overdue
                $colors = "AND ((i.due_date <= '{$today}' AND i.due_datetime < '{$now}') OR i.due_date < '{$today}' OR i.priority = 3)";
                break;
            case pocketlistsUserSettings::ICON_OVERDUE_TODAY: // overdue + today
                $colors = "AND (i.due_date <= '" . $today . "' OR i.due_datetime < '" . $tomorrow . "' OR i.priority IN (2, 3))";
                break;
            case pocketlistsUserSettings::ICON_OVERDUE_TODAY_AND_TOMORROW: // overdue + today + tomorrow
                $colors = "AND (i.due_date <= '" . $tomorrow . "' OR i.due_datetime < '" . $day_after_tomorrow . "' OR i.priority IN (1, 2, 3))";
                break;
            default:
                return '';
        }

        $q = "
          SELECT
            i.id
          FROM {$this->table} i
          LEFT JOIN pocketlists_list l ON (l.id = i.list_id  OR l.id = i.key_list_id)
          WHERE
            (
              (i.assigned_contact_id = i:contact_id) /* + items assigned to me */
              OR i.priority > 0 /* + items with priority */
              OR
              (
                /*((i.due_date IS NOT NULL OR i.due_datetime IS NOT NULL) AND i.list_id IS NULL AND i.key_list_id IS NULL AND i.contact_id = 7)
                OR
                ((i.due_date IS NOT NULL OR i.due_datetime IS NOT NULL) AND (i.list_id IS NOT NULL OR i.key_list_id IS NOT NULL)) */
                (i.due_date IS NOT NULL OR i.due_datetime IS NOT NULL) /* + items with due */
                AND
                IF(i.list_id IS NULL AND i.key_list_id IS NULL, i.contact_id = i:contact_id, i.contact_id > 0) /* + and if item is from NULL-list check contact_id */
              )
              OR (i.contact_id = i:contact_id AND i.list_id IS NULL AND i.key_list_id IS NULL) /* + my items from NULL-list */
            )
            AND (l.archived = 0 OR l.archived IS NULL) /* ONLY not completed items */
            AND i.status = 0
            AND (i.assigned_contact_id = i:contact_id OR i.assigned_contact_id IS NULL OR i.assigned_contact_id = 0) /* ONLY assigned to me or noone */
            {$colors} /* selected option */
            {$pocket_rights}";

        if ($icon !== false && $icon != pocketlistsUserSettings::ICON_NONE) {
            $count = $this->query($q, array(
                'contact_id' => wa()->getUser()->getId(),
                'list_ids'   => $lists
            ))->count();
            return $count;
        } else {
            return null;
        }
    }

    /**
     * @param $name
     * @param $datetime
     * @return array
     */
    public function getItemByNameAndCreatedDatetime($name, $datetime)
    {
        return $this->query("SELECT * FROM {$this->table} WHERE name = s:name AND 
 create_datetime BETWEEN (s:d1, s:d2) LIMIT 1", array(
            'name' => $name,
            'd1'   => date('Y-m-d H:i:s', strtotime($datetime) - 60),
            'd2'   => date('Y-m-d H:i:s', strtotime($datetime) + 60),
        ))->fetch();
    }

}
