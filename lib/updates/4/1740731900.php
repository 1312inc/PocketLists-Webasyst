<?php

$model = new pocketlistsModel();

try {
    $model->exec("ALTER TABLE pocketlists_list MODIFY COLUMN pocket_id int DEFAULT NULL");
} catch (waDbException $wdb_ex) {
}
try {
    $model->exec("UPDATE pocketlists_list SET pocket_id = NULL WHERE pocket_id = 0");
} catch (waDbException $wdb_ex) {
}
