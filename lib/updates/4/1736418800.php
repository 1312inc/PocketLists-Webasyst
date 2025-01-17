<?php

$model = new pocketlistsModel();

try {
    $model->exec("SELECT storage FROM pocketlists_attachment");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_attachment ADD `storage` ENUM('public', 'protected') DEFAULT 'protected' AFTER `filetype`");
}

try {
    $model->exec("UPDATE pocketlists_attachment SET storage='public'");
} catch (waDbException $wdb_ex) {
}

// Setup auto thumbnail generation for pocketlists image attachments
$path = wa()->getDataPath('attachments', true, pocketlistsHelper::APP_ID);
waFiles::write($path.'/thumb.php', '<?php
$file = realpath(dirname(__FILE__)."/../../../../")."/wa-apps/'.pocketlistsHelper::APP_ID.'/lib/config/data/thumb.php";

if (file_exists($file)) {
    include($file);
} else {
    header("HTTP/1.0 404 Not Found");
}
');
waFiles::copy(wa()->getAppPath('lib/config/data/.htaccess', pocketlistsHelper::APP_ID), $path.'/.htaccess');
