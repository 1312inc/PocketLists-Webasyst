<?php

$m = new pocketlistsModel();

try {
    $m->exec('select pro_label_id from pocketlists_item');
} catch (Exception $e) {
    $m->exec('alter table pocketlists_item change pro_label pro_label_id int null');
}
