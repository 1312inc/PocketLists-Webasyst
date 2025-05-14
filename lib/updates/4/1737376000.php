<?php

$model = new pocketlistsModel();

try {
    $model->exec("ALTER TABLE pocketlists_attachment DROP COLUMN `filetype`");
} catch (waDbException $wdb_ex) {
}

try {
    $model->exec("ALTER TABLE pocketlists_attachment ADD `ext` varchar(10) DEFAULT '' AFTER `filename`");
} catch (waDbException $wdb_ex) {
}

try {
    $model->exec("
        UPDATE pocketlists_attachment 
        SET `ext` = IF (SUBSTRING_INDEX(`filename`, '.', -1) = `filename`, '', LOWER(SUBSTRING_INDEX(`filename`, '.', -1)))
    ");
} catch (waDbException $wdb_ex) {
}
