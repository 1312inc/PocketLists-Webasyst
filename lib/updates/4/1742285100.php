<?php

$model = new pocketlistsModel();

try {
    $model->exec("SELECT is_private FROM pocketlists_list WHERE 0");
    $model->exec("ALTER TABLE pocketlists_list CHANGE is_private private tinyint DEFAULT 0 NULL");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_list ADD `private` tinyint DEFAULT 0 AFTER `icon`");
}
