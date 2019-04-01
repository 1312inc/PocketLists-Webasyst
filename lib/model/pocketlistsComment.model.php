<?php

/**
 * Class pocketlistsCommentModel
 *
 * @property int    $item_id
 * @property int    $contact_id
 * @property string $comment
 * @property string $create_datetime
 */
class pocketlistsCommentModel extends kmModelExt
{
    protected $table = 'pocketlists_comment';

    /**
     * @param array|int $item_ids
     *
     * @return array
     */
    public function getAllByItems($item_ids)
    {
        if (!is_array($item_ids)) {
            $item_ids = [$item_ids];
        }

        return $this->query("
            {$this->getSql()}
            WHERE item_id IN (i:ids)
            ORDER BY item_id, id",
            ['ids' => $item_ids]
        )->fetchAll('item_id', 2);
    }

    /**
     * @param array $comment
     *
     * @return array
     * @throws waException
     */
    public static function extendData($comment)
    {
        $comment_user = new waContact($comment['contact_id']);

        return $comment + [
                'my'             => $comment['contact_id'] == wa()->getUser()->getId() ? true : false,
                'contact'        => pocketlistsHelper::getContactData($comment_user),
                'can_be_deleted' => (time() - strtotime($comment['create_datetime']) < 60 * 60 * 24),
            ];
    }

    /**
     * @param int $start
     * @param int $limit
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    public function getComments($start = 0, $limit = 50)
    {
        $user_id = wa()->getUser()->getId();
        $lists = [];
        $list_sql = pocketlistsRBAC::filterListAccess($lists, $user_id);

        $q = "{$this->getSql()}
            WHERE {$list_sql}
            ORDER BY id DESC
            LIMIT {$start}, {$limit}";

        $comments = $this->query(
            $q,
            [
                'list_ids' => $lists,
                'start'    => $start,
                'limit'    => $limit,
            ]
        )->fetchAll();

        return $comments;
    }

    /**
     * @param int $user_last_activity
     *
     * @return int
     */
    public function getLastActivityComments($user_last_activity)
    {
        $user_id = wa()->getUser()->getId();
        $lists = [];
        $list_sql = pocketlistsRBAC::filterListAccess($lists, $user_id);

        $q = "SELECT 
                c.id
            FROM {$this->table} c
            LEFT JOIN pocketlists_item as i ON i.id = c.item_id
            left JOIN pocketlists_list as l ON l.id = i.list_id 
            WHERE 
              {$list_sql}
              AND c.create_datetime > s:user_last_activity";

        return $this->query(
            $q,
            [
                'list_ids'           => $lists,
                'user_last_activity' => $user_last_activity,
            ]
        )->count();
    }

    private function getSql()
    {
        return "SELECT 
                c.id id,
                c.item_id item_id,
                i.name item_name,
                l.id list_id,
                i2.name list_name,
                l.color list_color,
                c.contact_id contact_id,
                c.comment comment,
                c.create_datetime create_datetime,
                p.id pocket_id,
                p.name pocket_name
            FROM {$this->table} c
            LEFT JOIN pocketlists_item as i ON i.id = c.item_id
            LEFT JOIN pocketlists_list as l ON l.id = i.list_id
            LEFT JOIN pocketlists_item as i2 ON i2.key_list_id = l.id
            LEFT JOIN pocketlists_pocket p ON p.id = l.pocket_id";
    }
}
