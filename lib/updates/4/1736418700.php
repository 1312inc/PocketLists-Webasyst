<?php

$model = new pocketlistsModel();

try {
    $model->exec("SELECT storage FROM pocketlists_attachment");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_attachment ADD `storage` ENUM('public', 'protected') DEFAULT 'protected' AFTER `filetype`");
}

try {
    $model->exec("UPDATE pocketlists_attachment SET storage='public'");
} catch (waDbException $wdb_ex) {
}
