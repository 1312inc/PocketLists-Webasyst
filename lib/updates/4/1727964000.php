<?php

$model = new pocketlistsModel();

try {
    $model->exec("ALTER TABLE pocketlists_location MODIFY COLUMN location_latitude decimal(10,8) NULL");
} catch (waDbException $wdb_ex) {
}
try {
    $model->exec("ALTER TABLE pocketlists_location MODIFY COLUMN location_longitude decimal(10,8) NULL");
} catch (waDbException $wa_db_exception) {
}
try {
    $model->exec("ALTER TABLE pocketlists_location MODIFY COLUMN location_radius int NULL");
} catch (waDbException $wa_db_exception) {
}




