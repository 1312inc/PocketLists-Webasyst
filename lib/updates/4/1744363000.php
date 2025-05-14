<?php

$model = new pocketlistsModel();

try {
    $model->exec("ALTER TABLE pocketlists_item DROP COLUMN `repeat`");
} catch (waDbException $wdb_ex) {
}
