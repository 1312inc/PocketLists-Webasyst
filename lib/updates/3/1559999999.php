<?php

$m = new pocketlistsModel();

try {
    $m->exec('select id from pocketlists_attachment');
}catch (waException $ex) {
    $m->exec('alter table pocketlists_attachment add id int not null auto_increment primary key first;');
}
