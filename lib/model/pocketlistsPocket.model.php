<?php

class pocketlistsPocketModel extends waModel
{
    protected $table = 'pocketlists_pocket';

    /**
     * @param array|bool|false $rights
     * @return array
     */
    public function getAllPockets($rights = false)
    {
        $where_ids = '';
        $accessed_pockets = array();
        if ($rights) {
            foreach($rights as $pocket => $value) {
                $p = explode(".", $pocket);
                if (count($p) == 2) {
                    $accessed_pockets[$p[1]] = array(
                        'pocket_id' => $p[1],
                        'access' => $value
                    );
                }
            }
            $where_ids = 'WHERE id IN (i:access_id)';
        }

        $sql = "SELECT * FROM {$this->table} {$where_ids} ORDER BY id ASC ";
        return $this->query($sql, array(
            'access_id' => array_keys($accessed_pockets)
        ))->fetchAll();
    }
}