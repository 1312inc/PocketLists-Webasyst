<?php

$appPath = wa()->getAppPath(null, 'pocketlists');
$path = [
    '/templates/include/item_linked_entities/shop.order.autocomplete.html',
];

foreach ($path as $item) {
    try {
        waFiles::delete($appPath.$item, true);
    } catch (Exception $ex) {
        pocketlistsLogger::log('Error on deleting file '.$appPath.$item, 'meta.log');
    }
}
