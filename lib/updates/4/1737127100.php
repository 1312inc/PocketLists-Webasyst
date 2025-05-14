<?php

$model = new pocketlistsModel();

try {
    $model->exec("ALTER TABLE pocketlists_attachment ADD `size` int (11) DEFAULT NULL AFTER `filename`");
} catch (waDbException $wdb_ex) {
}
