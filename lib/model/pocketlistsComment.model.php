<?php

class pocketlistsCommentModel extends waModel
{
    protected $table = 'pocketlists_comment';

    private $lists = array();

    public function getAllByItems($item_ids)
    {
        if (!is_array($item_ids)) {
            $item_ids = array($item_ids);
        }
        return $this->query(
            "SELECT * FROM {$this->table} WHERE item_id IN (i:ids) ORDER BY item_id, id",
            array('ids' => $item_ids)
        )->fetchAll('item_id', 2);
    }

    public static function extendData($comment)
    {
        $comment_user = new waContact($comment['contact_id']);
        return $comment + array(
            'my'             => $comment['contact_id'] == wa()->getUser()->getId() ? true : false,
            'username'       => $comment_user->getName(),
            'login'          => $comment_user->get('login'),
            'userpic'        => $comment_user->getPhoto('20'),
            'can_be_deleted' => (time() - strtotime($comment['create_datetime']) < 60 * 60 * 24),
        );
    }

    private function filterAccess($user_id)
    {
        $list_sql = null;
        $this->lists[$user_id] = array();
        if (!pocketlistsRBAC::isAdmin($user_id)) {
            if ($this->lists[$user_id] = pocketlistsRBAC::getAccessListForContact($user_id)) {
                $list_sql[] = "l.id IN (i:list_ids) /* accessed lists*/";
            }
            if (pocketlistsRBAC::canAssign($user_id)) {
                $list_sql[] = "l.id IS NULL /* null list */";
            }
        } else {
            $list_sql = '1';
        }
        $list_sql = '(' . (is_array($list_sql) ? implode(' OR ', $list_sql) : $list_sql) . ')';

        return $list_sql;
    }

    public function getComments($start = 0, $limit = 50)
    {
        $user_id = wa()->getUser()->getId();
        $list_sql = $this->filterAccess($user_id);

        $q = "SELECT 
                c.id id,
                c.item_id item_id,
                i.name item_name,
                l.id list_id,
                c.contact_id contact_id,
                c.comment comment,
                c.create_datetime create_datetime
            FROM {$this->table} c
            LEFT JOIN pocketlists_item as i ON i.id = c.item_id
            LEFT JOIN pocketlists_list as l ON l.id = i.list_id
            WHERE {$list_sql}
            ORDER BY id DESC
            LIMIT {$start}, {$limit}";

        $comments = $this->query($q, array(
            'list_ids' => $this->lists[$user_id],
            'start'    => $start,
            'limit'    => $limit,
        ))->fetchAll();

        return array_map(array('pocketlistsCommentModel', 'extendData'), $comments);
    }

    public function getLastActivityComments($user_last_activity)
    {
        $user_id = wa()->getUser()->getId();
        $list_sql = $this->filterAccess($user_id);

        $q = "SELECT 
                c.id
            FROM {$this->table} c
            LEFT JOIN pocketlists_item as i ON i.id = c.item_id
            left JOIN pocketlists_list as l ON l.id = i.list_id 
            WHERE
              {$list_sql} 
              AND c.create_datetime > s:user_last_activity";

        return $this->query($q, array(
            'list_ids'           => $this->lists[$user_id],
            'user_last_activity' => $user_last_activity,
        ))->count();
    }
}
