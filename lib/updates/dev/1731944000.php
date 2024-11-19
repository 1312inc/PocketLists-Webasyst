<?php

$model = new pocketlistsModel();

try {
    $model->exec("ALTER TABLE pocketlists_location MODIFY COLUMN `location_latitude` decimal(12,8) NULL");
    $model->exec("ALTER TABLE pocketlists_location MODIFY COLUMN `location_longitude` decimal(12,8) NULL");
} catch (waDbException $wdb_ex) {
}
