<?php

$appPath = wa()->getAppPath(null, 'pocketlists');
$path = [
    '/lib/handlers/contacts.profile.tab.handler.php',
    '/lib/handlers/shop.backend_order.handler.php',
    '/lib/handlers/shop.backend_orders.handler.php',
    '/lib/handlers/webasyst.backend_header.handler.php',
    '/templates/include/item_linked_entities/shop.order.autocomplete.html',
    '/lib/class/pocketlistsContact.class.php',
    '/lib/actions/json',
    '/templates/include/itemadd.html',
];

foreach ($path as $item) {
    try {
        waFiles::delete($appPath.$item, true);
    } catch (Exception $ex) {
        pocketlistsLogger::log('Error on deleting file '.$appPath.$item, 'meta.log');
    }
}
