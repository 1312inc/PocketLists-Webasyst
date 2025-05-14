<?php

$entities = [
    pocketlistsLogContext::POCKET_ENTITY,
    // pocketlistsLogContext::LIST_ENTITY, -> ITEM_ENTITY
    pocketlistsLogContext::ITEM_ENTITY,
    pocketlistsLogContext::COMMENT_ENTITY,
    pocketlistsLogContext::ATTACHMENT_ENTITY,
    pocketlistsLogContext::LOCATION_ENTITY
];

$model = new pocketlistsModel();

foreach ($entities as $_entity) {
    try {
        $model->exec("SELECT uuid FROM pocketlists_$_entity");
    } catch (waDbException $wdb_ex) {
        $model->exec("ALTER TABLE pocketlists_$_entity ADD `uuid` varchar(36) DEFAULT NULL");
    }
}
