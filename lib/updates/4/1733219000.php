<?php

$model = new pocketlistsModel();

try {
    $model->exec("ALTER TABLE pocketlists_pocket ADD `create_datetime` datetime DEFAULT NULL AFTER `color`");
    $model->exec("ALTER TABLE pocketlists_pocket ADD `update_datetime` datetime DEFAULT NULL AFTER `create_datetime`");
} catch (waDbException $wdb_ex) {
}
