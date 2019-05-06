<?php

$appPath = wa()->getAppPath(null, 'pocketlists');
$path = [
    '/lib/class/Entity/pocketlistsTeammate.class.php',
    '/lib/class/Factory/pocketlistsTeammateFactory.class.php',
    '/lib/class/ItemLink/',
    '/lib/class/pocketlistsNotifications.class.php',
    '/lib/model/pocketlistsItemTag.model.php',
    '/lib/vendor/km/kmModelExt.class.php',
    '/lib/vendor/km/kmModelStorage.class.php',
    '/templates/mails/newfavoritelistitem.html',
];

foreach ($path as $item) {
    try {
        waFiles::delete($appPath.$item, true);
    } catch (Exception $ex) {
        waLog::log('Error on deleting file ' . $appPath.$item, 'pocketlists/update.log');
    }
}
