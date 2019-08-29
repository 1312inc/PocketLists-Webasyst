<?php

$m = new pocketlistsModel();

try {
    $m->exec('select assigned_contact_id from pocketlists_log');
}catch (waException $ex) {
    $m->exec('alter table pocketlists_log add assigned_contact_id int null after additional_id');
}
