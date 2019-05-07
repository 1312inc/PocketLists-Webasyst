<?php

$m = new pocketlistsModel();

try {
    $m->exec(
        'create table pocketlists_notification
        (
            id         int auto_increment  primary key,
            type       smallint(6)          not null,
            created_at datetime             not null,
            sent_at    datetime             null,
            status     varchar(255)         null,
            error      text charset utf8mb4 null,
            data       text charset utf8mb4 null
        )');
} catch (Exception $ex) {
    waLog::log('Error on creating pocketlists_notification.', 'pocketlists/update.log');
}
