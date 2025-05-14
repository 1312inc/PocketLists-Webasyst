<?php

/**
 * Class pocketlistsItemMoveModel
 */
class pocketlistsItemMoveModel extends pocketlistsModel
{
    /** @var string */
    protected $table = 'pocketlists_item_move';

    public function multipleInsert($data)
    {
        foreach ($data as &$_d) {
            if (empty($_d['datetime'])) {
                $_d['datetime'] = date('Y-m-d H:i:s');
            }
        }

        return parent::multipleInsert($data);
    }
}
