<?php

$model = new pocketlistsModel();

try {
    $model->exec("SELECT prev_status FROM pocketlists_item_move WHERE 0");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_item_move ADD `prev_status` tinyint DEFAULT NULL AFTER `prev_pocket_id`");
}
