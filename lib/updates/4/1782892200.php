<?php

$model = new pocketlistsModel();

try {
    $model->exec("SELECT template FROM pocketlists_list WHERE 0");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_list ADD template tinyint DEFAULT 0 AFTER `archived`");
}
