<?php

$model = new pocketlistsModel();

try {
    $model->exec("SELECT * FROM pocketlists_item_move");
} catch (waDbException $wdb_ex) {
    $model->exec("
        CREATE TABLE `pocketlists_item_move` (
            `item_id` int(11) NOT NULL,
            `prev_list_id` int(11) DEFAULT NULL,
            `prev_pocket_id` int(11) DEFAULT NULL,
            `datetime` datetime DEFAULT NULL 
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ");
}
