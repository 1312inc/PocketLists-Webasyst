<?php

$path = wa()->getDataPath('attachments', true, 'pocketlists');
waFiles::delete($path.'/thumb.php');
waFiles::write($path.'/thumb.php', '<?php
$file = dirname(__FILE__)."/../../../../"."/wa-apps/pocketlists/lib/config/data/thumb.php";

if (file_exists($file)) {
    include($file);
} else {
    if (class_exists("waLog")) {
        waLog::log(["$file" => $file], "pocketlists/thumb.log");
    }
    header("HTTP/1.0 404 Not Found");
}
');

waFiles::delete($path.'/.htaccess');
waFiles::copy(wa()->getAppPath('lib/config/data/.htaccess', 'pocketlists'), $path.'/.htaccess');
