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
        $select_pocket = "";
        if ($pocket_id) {
            $select_pocket = " AND l.pocket_id = i:pocket_id";
        }
        $sql = "SELECT
                  l.*,
                  COUNT(i.id) 'count'
                FROM pocketlists_list l
                LEFT JOIN pocketlists_item i ON i.list_id = l.id AND i.status = 0
                WHERE
                  l.archived = i:archived
                  {$select_pocket}
                GROUP BY l.id";

        return $this->query($sql, array(
            'archived' => 0,
            'pocket_id' => $pocket_id
        ))->fetchAll();
    }

}