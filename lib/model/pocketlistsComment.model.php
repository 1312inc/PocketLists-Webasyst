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
            "SELECT * FROM {$this->table} WHERE list_id IN (i:ids)",
            array('ids' => $item_ids)
        )->fetchAll('item_id', 2);
    }
}
