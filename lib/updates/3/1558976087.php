<?php

$m = new waModel();

try {
    $m->exec("alter table pocketlists_item_link change `entity_type` `entity_type` varchar(50) NOT NULL DEFAULT ''");
} catch (waException $ex) {
    waLog::log('Error on altering pocketlists_item_link', 'pocketlists/update.log');
}
