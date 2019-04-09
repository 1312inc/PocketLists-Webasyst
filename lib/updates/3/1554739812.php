<?php

try {
    (new waModel())->exec('update pocketlists_list set archived = 0 where archived is null');
} catch (waException $ex) {
    waLog::log('Error on nulling list archived flag.', 'pocketlists/update.log');
}
