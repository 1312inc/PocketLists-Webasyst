<?php

class pocketlistsListModel extends waModel
{
    protected $table = 'pocketlists_list';

    public function getList($id)
    {
        return $this->query(
            "SELECT
              i.*,
              l.*
            FROM {$this->table} l
            LEFT JOIN pocketlists_item i ON i.key_list_id = l.id
            WHERE l.id = i:id",
            array('id' => $id)
        )->fetchAssoc();
    }

    public function add($data, $type = false)
    {
        $inserted_list_id = $this->insert($data, $type);
        $data = $data + array('key_list_id',$inserted_list_id);
        $im = new pocketlistsItemModel();
        $inserted_item_id = $im->insert($data, $type);
        $this->updateById($data['key_list_id'], array('key_list_id' => $inserted_item_id));
        return $inserted_list_id ? $data : false;
    }

    public function update($id, $data)
    {
        $im = new pocketlistsItemModel();
        $im->updateByField('key_list_id', $id, $data);
        return $this->updateById($id, $data);
    }

    public function delete($id)
    {
        $im = new pocketlistsItemModel();
        $im->deleteByField('list_id', $id);
        $im->deleteByField('key_list_id', $id);
        return $this->deleteById($id);
    }

    public function getLists($pocket_id = false)
    {
        $select_pocket = "";
        if ($pocket_id) {
            $select_pocket = " AND l.pocket_id = i:pocket_id";
        }
        $sql = "SELECT
                  i2.*,
                  l.*,
                  COUNT(i.id) 'count'
                FROM pocketlists_list l
                LEFT JOIN pocketlists_item i ON i.list_id = l.id AND i.status = 0
                LEFT JOIN pocketlists_item i2 ON i2.key_list_id = l.id
                WHERE
                  l.archived = i:archived
                  {$select_pocket}
                GROUP BY l.id";

        return $this->query(
            $sql,
            array(
                'archived' => 0,
                'pocket_id' => $pocket_id
            )
        )->fetchAll();
    }

}