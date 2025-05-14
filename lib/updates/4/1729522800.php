<?php

$model = new pocketlistsModel();

/** LOCATION */
try {
    $model->exec("SELECT `name` FROM pocketlists_location");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_location ADD `name` varchar(255) DEFAULT '' AFTER `id`");
}

try {
    $model->exec("SELECT `color` FROM pocketlists_location");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_location ADD `color` varchar(32) DEFAULT '' AFTER `name`");
}
