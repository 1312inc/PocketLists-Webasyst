<?php

$model = new pocketlistsModel();

try {
    $model->exec("ALTER TABLE pocketlists_comment ADD `update_datetime` datetime DEFAULT NULL AFTER `create_datetime`");
} catch (waDbException $wdb_ex) {
}
