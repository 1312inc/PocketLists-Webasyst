<?php

$m = new pocketlistsModel();

try {
    $m->exec('create index pro_label_id on pocketlists_item (pro_label_id)');
} catch (waException $ex) {
}
