<?php

$path = wa()->getDataPath('attachments', true, pocketlistsHelper::APP_ID);
waFiles::delete($path.'/thumb.php');
waFiles::write($path.'/thumb.php', '<?php
$file = dirname(__FILE__)."/../../../../"."/wa-apps/'.pocketlistsHelper::APP_ID.'/lib/config/data/thumb.php";

if (file_exists($file)) {
    include($file);
} else {
    header("HTTP/1.0 404 Not Found");
}
');
