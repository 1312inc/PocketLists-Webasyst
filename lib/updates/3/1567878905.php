<?php

$m = new pocketlistsModel();

try {
    $m->exec('select identifier from pocketlists_notification');
}catch (waException $ex) {
    $m->exec('alter table pocketlists_notification add identifier varchar(32) after type');
}
