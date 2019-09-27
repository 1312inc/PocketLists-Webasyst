<?php

$m = new pocketlistsModel();

try {
    $m->exec('alter table pocketlists_notification modify type varchar(30) not null');
}catch (waException $ex) {
}
