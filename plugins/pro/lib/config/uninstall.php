<?php

$m = new pocketlistsModel();

try {
    $m->exec('alter table pocketlists_item drop column pro_label');
} catch (Exception $ex) {}
