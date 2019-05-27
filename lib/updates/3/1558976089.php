<?php

$m = new pocketlistsModel();

try {
    $m->exec('drop index pocketlists_notification_sent_at_index on pocketlists_notification');
} catch (Exception $ex) {
}

try {
    $m->exec('create index pocketlists_notification_status_index on pocketlists_notification (status)');
} catch (Exception $ex) {
}

