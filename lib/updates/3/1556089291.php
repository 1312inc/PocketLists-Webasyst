<?php

$m = new pocketlistsModel();

$updates = require wa()->getAppPath('lib/config/utf8mb4.php', pocketlistsHelper::APP_ID);

try {
    foreach ($updates as $table => $columns) {
        foreach ($columns as $column => $type) {
            $m->exec(
                sprintf(
                    'alter table %s change %s %s %s character set utf8mb4 collate utf8mb4_unicode_ci',
                    $table,
                    $column,
                    $column,
                    $type
                )
            );
        }
    }
} catch (Exception $ex) {
    waLog::log('Error on altering to utf8mb4.', 'pocketlists/update.log');
}
