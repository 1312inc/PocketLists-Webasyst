<?php

$model = new pocketlistsModel();

/** POCKET */
try {
    $model->exec("SELECT `rank` FROM pocketlists_pocket");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_pocket ADD `rank` varchar(16) DEFAULT '' AFTER `sort`");
}
try {
    $model->exec("ALTER TABLE pocketlists_pocket DROP INDEX `sort`");
} catch (waDbException $wa_db_exception) {
}
try {
    $model->exec("ALTER TABLE pocketlists_pocket ADD INDEX `sort` (`sort`, `rank`)");
} catch (waDbException $wa_db_exception) {
}

/** LIST */
try {
    $model->exec("SELECT `rank` FROM pocketlists_list");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_list ADD `rank` varchar(16) DEFAULT '' AFTER `sort`");
}
try {
    $model->exec("ALTER TABLE pocketlists_list DROP INDEX `sort`");
} catch (waDbException $wa_db_exception) {
}
try {
    $model->exec("ALTER TABLE pocketlists_list ADD INDEX `sort` (`sort`, `rank`)");
} catch (waDbException $wa_db_exception) {
}

/** ITEM */
try {
    $model->exec("SELECT `rank` FROM pocketlists_item");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_item ADD `rank` varchar(16) DEFAULT '' AFTER `sort`");
}
try {
    $model->exec("ALTER TABLE pocketlists_item DROP INDEX `sort`");
} catch (waDbException $wa_db_exception) {
}
try {
    $model->exec("ALTER TABLE pocketlists_item ADD INDEX `sort` (`parent_id`, `sort`, `rank`)");
} catch (waDbException $wa_db_exception) {
}
