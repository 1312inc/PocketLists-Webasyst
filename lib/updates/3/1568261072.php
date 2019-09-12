<?php

$m = new pocketlistsModel();

try {
    $m->exec('alter table pocketlists_notification modify identifier varchar(50) null');
}catch (waException $ex) {
}
