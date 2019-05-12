<?php

$m = new waModel();

try {
    $m->exec('delete from pocketlists_item_link where entity_id = 0');
    $m->exec("update pocketlists_item_link set app = 'shop', entity_type = 'order'");
} catch (waException $ex) {
    waLog::log('Error on updating pocketlists_item_link', 'pocketlists/update.log');
}
