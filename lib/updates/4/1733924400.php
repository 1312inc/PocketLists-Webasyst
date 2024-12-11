<?php

$model = new pocketlistsModel();

try {
    $model->exec("ALTER TABLE pocketlists_item ADD `activity_datetime` datetime DEFAULT NULL AFTER `update_datetime`");
} catch (waDbException $wdb_ex) {
}

try {
    $model->exec("ALTER TABLE pocketlists_location ADD `activity_datetime` datetime DEFAULT NULL AFTER `update_datetime`");
} catch (waDbException $wdb_ex) {
}

try {
    $model->exec("ALTER TABLE pocketlists_pocket ADD `activity_datetime` datetime DEFAULT NULL AFTER `update_datetime`");
} catch (waDbException $wdb_ex) {
}

try {
    $model->exec("UPDATE pocketlists_item SET activity_datetime = update_datetime WHERE update_datetime IS NOT NULL");
    $model->exec("UPDATE pocketlists_location SET activity_datetime = update_datetime WHERE update_datetime IS NOT NULL");
    $model->exec("UPDATE pocketlists_pocket SET activity_datetime = update_datetime WHERE update_datetime IS NOT NULL");
} catch (waDbException $wdb_ex) {
}
