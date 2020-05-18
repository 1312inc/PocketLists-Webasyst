<?php

$m = new pocketlistsModel();

try {
    $m->exec('create index item_id on pocketlists_comment (item_id)');
} catch (waException $ex) {
}

try {
    $m->exec('create index complete_contact_id on pocketlists_item (complete_contact_id)');
} catch (waException $ex) {
}

try {
    $m->exec('create index contact_id on pocketlists_item (contact_id)');
} catch (waException $ex) {
}

try {
    $m->exec('create index assigned_contact_id on pocketlists_item (assigned_contact_id)');
} catch (waException $ex) {
}

try {
    $m->exec('create index contact_id on pocketlists_comment (contact_id)');
} catch (waException $ex) {
}
