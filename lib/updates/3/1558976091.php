<?php

$appPath = wa()->getAppPath(null, 'pocketlists');
$path = [
    '/lib/model/pocketlistsNotificationModel.class.php',
];

foreach ($path as $item) {
    try {
        waFiles::delete($appPath.$item, true);
    } catch (Exception $ex) {
        waLog::log('Error on deleting file '.$appPath.$item, 'pocketlists/update.log');
    }
}
