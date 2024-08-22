<?php

$model = new pocketlistsModel();

try {
    $model->exec("UPDATE pocketlists_pocket SET `sort` = '0' WHERE `sort` NOT REGEXP '^[[:digit:]]+$'");
    $model->exec("ALTER TABLE pocketlists_pocket MODIFY COLUMN `sort` INT DEFAULT 0 NOT NULL");
} catch (waDbException $wdb_ex) {
}

try {
    $model->exec("UPDATE pocketlists_list SET `sort` = '0' WHERE `sort` NOT REGEXP '^[[:digit:]]+$'");
    $model->exec("ALTER TABLE pocketlists_list MODIFY COLUMN `sort` INT DEFAULT 0 NOT NULL");
} catch (waDbException $wdb_ex) {
}

try {
    $model->exec("UPDATE pocketlists_item SET `sort` = '0' WHERE `sort` NOT REGEXP '^[[:digit:]]+$'");
    $model->exec("ALTER TABLE pocketlists_item MODIFY COLUMN `sort` INT DEFAULT 0 NOT NULL");
} catch (waDbException $wdb_ex) {
}
