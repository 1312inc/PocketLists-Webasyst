<?php

$m = new pocketlistsModel();

try {
    $m->exec('select * from pocketlists_log');
} catch (waException $ex) {
    $m->exec('create table pocketlists_log
        (
            id               bigint auto_increment primary key,
            action           varchar(30) not null,
            entity_type      varchar(30) null,
            contact_id       int         null,
            pocket_id        int         null,
            list_id          int         null,
            item_id          int         null,
            comment_id       int         null,
            attachment_id    int         null,
            location_id      int         null,
            additional_id    int         null,
            params           text        null,
            create_datetime  datetime    null
        )');
    $m->exec('create index pocketlists_log_action_index on pocketlists_log (action)');
    $m->exec('create index pocketlists_log_contact_id_index on pocketlists_log (contact_id)');
    $m->exec('create index pocketlists_log_item_id_index on pocketlists_log (item_id)');
    $m->exec('create index pocketlists_log_list_id_index_2 on pocketlists_log (list_id)');
    $m->exec('create index pocketlists_log_pocket_id_index on pocketlists_log (pocket_id)');
}