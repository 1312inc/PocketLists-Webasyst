<?php

$model = new pocketlistsModel();

try {
    $model->exec("SELECT repeat_frequency FROM pocketlists_item WHERE 0");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_item ADD repeat_frequency int (11) DEFAULT 0 NOT NULL AFTER `assigned_contact_id`");
}

try {
    $model->exec("SELECT repeat_interval FROM pocketlists_item WHERE 0");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_item ADD repeat_interval varchar(20) DEFAULT NULL AFTER `repeat_frequency`");
}

try {
    $model->exec("SELECT repeat_occurrence FROM pocketlists_item WHERE 0");
} catch (waDbException $wdb_ex) {
    $model->exec("ALTER TABLE pocketlists_item ADD repeat_occurrence int (11) DEFAULT NULL AFTER `repeat_interval`");
}
