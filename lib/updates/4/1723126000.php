<?php

$model = new pocketlistsModel();

try {
    $model->exec("DROP TABLE IF EXISTS pocketlists_item_sort, pocketlists_list_sort");
} catch (waDbException $wdb_ex) {
}
