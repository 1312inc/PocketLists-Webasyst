<?php

class pocketlistsListModel extends waModel
{
    protected $table = 'pocketlists_list';

    public function getList($id)
    {
        return $this->getById($id);
    }

    public function getLists($pocket_id = false)
    {
        if ($pocket_id) {
            return $this->getByField(array(
                'archived' => 0,
                'pocket_id' => $pocket_id
            ), true);
        } else {
            return $this->getByField(array(
                'archive' => 1
            ));
        }
    }

}