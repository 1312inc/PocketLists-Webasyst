<?php

$m = new pocketlistsModel();

try {
    $m->exec('create index pocketlists_notification_status_index on pocketlists_notification (status)');
} catch (Exception $ex) {
    waLog::log('Error on creating pocketlists_notification index.', 'pocketlists/update.log');
}
