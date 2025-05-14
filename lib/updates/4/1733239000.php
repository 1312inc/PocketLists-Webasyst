<?php

$model = new pocketlistsModel();

try {
    $model->exec("ALTER TABLE pocketlists_attachment ADD `upload_datetime` datetime DEFAULT NULL AFTER `filetype`");
} catch (waDbException $wdb_ex) {
}
