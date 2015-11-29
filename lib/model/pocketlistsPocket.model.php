<?php

class pocketlistsPocketModel extends waModel
{
    protected $table = 'pocketlists_pocket';

    public function getAllPockets($access_ids = array())
    {
        $where_ids = '';
        if ($access_ids) {
            if (!is_array($access_ids)) {
                $access_ids = array($access_ids);
            }
            $where_ids = 'WHERE id IN (i:access_id)';
        }
        $sql = "SELECT * FROM {$this->table} {$where_ids} ORDER BY id ASC ";
        return $this->query($sql, array(
            'access_id' => array_keys($access_ids)
        ))->fetchAll();
    }
}