<?php

$model = new pocketlistsModel();

try {
    $model->exec("SELECT pro_label_id FROM pocketlists_item WHERE 0");
} catch (waDbException $wdb_ex) {
    $model->exec("
        ALTER TABLE pocketlists_item ADD pro_label_id INT DEFAULT NULL
    ");
}
