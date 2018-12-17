<?php

$appPath = wa()->getAppPath(null, 'pocketlists');
$path = [
    '/lib/class/Factory/pocketlistsFactoryItem.class.php',
    '/lib/class/Factory/pocketlistsFactoryItemLink.class.php',
];

foreach ($path as $item) {
    waFiles::delete($appPath.$item);
}
