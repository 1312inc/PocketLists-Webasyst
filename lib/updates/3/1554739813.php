<?php

try {
    (new waModel())->exec('update pocketlists_item set assigned_contact_id = null where assigned_contact_id = 0');
} catch (Exception $ex) {
    waLog::log('Error on nulling item assigned_contact_id flag.', 'pocketlists/update.log');
}
