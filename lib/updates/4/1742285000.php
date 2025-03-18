<?php

$model = new pocketlistsModel();

try {
    $model->exec("ALTER TABLE pocketlists_list ADD `is_private` tinyint DEFAULT 0 AFTER `icon`");
} catch (waDbException $wdb_ex) {
}
