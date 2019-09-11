<?php

$m = new pocketlistsModel();

try {
    $m->exec('select delayed_to from pocketlists_notification');
}catch (waException $ex) {
    $m->exec('alter table pocketlists_notification add delayed_to datetime after created_at');
    $m->exec('alter table pocketlists_notification add handler text after identifier');
}
