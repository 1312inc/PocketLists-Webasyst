<?php

$appPath = wa()->getAppPath(null, 'pocketlists');
$path = [
    '/lib/class/pocketlistsContact.class.php',
];

foreach ($path as $item) {
    try {
        waFiles::delete($appPath.$item, true);
    } catch (Exception $ex) {
        pocketlistsLogger::log('Error on deleting file '.$appPath.$item, 'meta.log');
    }
}
