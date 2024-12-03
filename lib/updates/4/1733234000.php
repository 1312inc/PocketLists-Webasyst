<?php

$model = new pocketlistsModel();

try {
    $model->exec("ALTER TABLE pocketlists_location ADD `create_datetime` datetime DEFAULT NULL AFTER `location_radius`");
    $model->exec("ALTER TABLE pocketlists_location ADD `update_datetime` datetime DEFAULT NULL AFTER `create_datetime`");
} catch (waDbException $wdb_ex) {
}
