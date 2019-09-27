<?php

$m = new pocketlistsModel();

try {
    $m->exec('alter table pocketlists_notification add direction varchar(10) null default \'external\'');
} catch (waException $ex) {
}
