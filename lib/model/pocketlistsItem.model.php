<?php

class pocketlistsItemModel extends waModel
{
    protected $table = 'pocketlists_item';

    public function getByList($list_id)
    {
        return $this->getByField('list_id', $list_id, true);
    }
}