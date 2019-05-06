<?php

$appPath = wa()->getAppPath(null, 'pocketlists');
$path = [
    '/js/pocketlists.min.js',
    '/js/pocketlists-item.min.js',
    '/js/pocketlists-list.min.js',
    '/js/pocketlists-routing.min.js',
    '/js/jquery-ui.min.old.js',
];


foreach ($path as $item) {
    try {
        waFiles::delete($appPath.$item);
    } catch (Exception $ex) {
        waLog::log('Error on deleting file ' . $appPath.$item, 'pocketlists/update.log');
    }
}
