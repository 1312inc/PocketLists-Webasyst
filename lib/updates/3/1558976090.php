<?php

$m = new pocketlistsModel();

try {
    $m->exec('create index key_list_id on pocketlists_item (key_list_id)');
} catch (Exception $ex) {
}
