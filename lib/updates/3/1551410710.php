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
    waFiles::delete($appPath.$item);
}
