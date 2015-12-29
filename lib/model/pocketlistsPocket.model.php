<?php

class pocketlistsPocketModel extends waModel
{
    protected $table = 'pocketlists_pocket';

    /**
     * @param $contact_id int
     * @return array
     */
    public function getAllPockets($contact_id = false)
    {
        $where_ids = '';
        $accessed_pockets = array();
        if ($contact_id) {
            $accessed_pockets = pocketlistsHelper::getAccessPocketForContact($contact_id);
            $where_ids = 'WHERE id IN (i:access_id)';
        }

        $sql = "SELECT * FROM {$this->table} {$where_ids} ORDER BY id ASC ";
        return $this->query($sql, array(
            'access_id' => $accessed_pockets
        ))->fetchAll();
    }
}
