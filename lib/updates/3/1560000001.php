<?php

$m = new pocketlistsModel();

try {
    $m->exec(sprintf("delete from wa_contact_rights where app_id = '%s' and group_id=%d", 'pocketlists', 0));
} catch (waException $ex) {
    waLog::log('Error on deleting from wa_contact_rights', 'pocketlists/update.log');
}