<?php

$appPath = wa()->getAppPath(null, 'pocketlists');
$path = [
    '/lib/model/pocketlistsListSharing.model.php',
    '/lib/model/pocketlistsUser.model.php',
    '/lib/model/pocketlistsUserContacts.model.php',
];

foreach ($path as $item) {
    waFiles::delete($appPath.$item);
}
