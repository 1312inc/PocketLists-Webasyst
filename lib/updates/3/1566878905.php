<?php

$m = new pocketlistsModel();

try {
    $m->exec('select app_id from pocketlists_notification');
}catch (waException $ex) {
    $m->exec('alter table pocketlists_notification add app_id varchar(40) null after id');
    $m->exec('alter table pocketlists_notification add delayed_to datetime null after created_at');
    $m->exec('alter table pocketlists_notification add identifier int null after app_id');
    $m->exec('create index pocketlists_notification_app_id on pocketlists_notification (app_id)');
    $m->exec('create index pocketlists_notification_identifier on pocketlists_notification (identifier)');
}
