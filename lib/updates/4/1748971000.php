<?php

$model = new pocketlistsModel();

try {
    $model->exec("
        UPDATE pocketlists_notification
        SET sent_at = s:sent_at, status = 1
        WHERE status = 0 AND created_at < s:created_at
    ", [
        'sent_at'    => date('Y-m-d H:i:s'),
        'created_at' => date('Y-m-d')
    ]);
} catch (waDbException $wdb_ex) {
}
