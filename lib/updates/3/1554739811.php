<?php

try {
    (new waModel())->exec('update pocketlists_item set list_id = null where list_id = 0');
} catch (Exception $ex) {
    waLog::log('Error on nulling list_id.', 'pocketlists/update.log');
}
