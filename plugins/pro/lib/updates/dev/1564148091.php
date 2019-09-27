<?php

$m = new pocketlistsModel();

try {
    $m->exec('select pro_label from pocketlists_item');
} catch (Exception $e) {
    $m->exec('alter table pocketlists_item add pro_label int null');
}
