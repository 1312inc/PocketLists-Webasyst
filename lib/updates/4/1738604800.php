<?php

$model = new pocketlistsModel();

/** ITEM */
try {
    $model->exec("SELECT `client_touch_datetime` FROM pocketlists_item");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_item ADD `client_touch_datetime` datetime DEFAULT NULL AFTER `due_datetime`");
}

/** COMMENT */
try {
    $model->exec("SELECT `client_touch_datetime` FROM pocketlists_comment");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_comment ADD `client_touch_datetime` datetime DEFAULT NULL AFTER `create_datetime`");
}

/** POCKET */
try {
    $model->exec("SELECT `client_touch_datetime` FROM pocketlists_pocket");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_pocket ADD `client_touch_datetime` datetime DEFAULT NULL AFTER `activity_datetime`");
}
