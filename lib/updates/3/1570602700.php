<?php

$m = new pocketlistsModel();

try {
    $m->exec('select contact_id from pocketlists_notification');
} catch (waException $ex) {
    $m->exec('alter table pocketlists_notification add contact_id int null');
}

try {
    $m->exec('select direction from pocketlists_notification');
} catch (waException $ex) {
    $m->exec('alter table pocketlists_notification add direction varchar(10) default \'external\'');
}

$m->exec('alter table pocketlists_notification modify identifier varchar(64) null');
