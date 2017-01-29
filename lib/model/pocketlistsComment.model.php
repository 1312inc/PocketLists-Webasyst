<?php

class pocketlistsCommentModel extends waModel
{
    protected $table = 'pocketlists_comment';

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
            'userpic'        => $comment_user->getPhoto('20'),
            'can_be_deleted' => (time() - strtotime($comment['create_datetime']) < 60 * 60 * 24),
        );
    }
}
