<?php

$appPath = wa()->getAppPath(null, 'pocketlists');
$path = [
    '/lib/updates/dev',
    '/lib/vendor/km/kmstatqueries.php',
];

foreach ($path as $item) {
    try {
        waFiles::delete($appPath.$item, true);
    } catch (Exception $ex) {
        waLog::log('Error on deleting file '.$appPath.$item, 'pocketlists/update.log');
    }
}
