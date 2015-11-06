<?php

class pocketlistsListModel extends waModel
{
    protected $table = 'pocketlists_list';

    public function getLists($pocket_id = false)
    {
        if ($pocket_id) {
            return $this->getAll();
        } else {
            return $this->getByField('pocket_id', $pocket_id);
        }
    }
}