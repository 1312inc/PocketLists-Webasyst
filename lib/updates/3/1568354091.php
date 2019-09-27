<?php

$m = new pocketlistsModel();

try {
    $m->exec('alter table pocketlists_notification add contact_id int(11) null');
} catch (waException $ex) {
}
