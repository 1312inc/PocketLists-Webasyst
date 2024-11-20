<?php

$model = new pocketlistsModel();

try {
    $model->exec("ALTER TABLE pocketlists_item_link MODIFY COLUMN entity_id VARCHAR(36) NOT NULL");
} catch (waDbException $wdb_ex) {
}
