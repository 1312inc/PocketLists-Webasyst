<?php

$model = new pocketlistsModel();

try {
    $sort_type = $model->query("
        SELECT DATA_TYPE 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'pocketlists_item' AND COLUMN_NAME = 'sort'
    ")->fetchField('DATA_TYPE');
    if ($sort_type == 'int') {
        $model->exec("
            ALTER TABLE pocketlists_item MODIFY COLUMN sort VARCHAR(32) DEFAULT '0' NOT NULL
        ");
    }
} catch (waDbException $wdb_ex) {
}

try {
    $sort_type = $model->query("
        SELECT DATA_TYPE 
        FROM INFORMATION_SCHEMA.COLUMNS 
        WHERE TABLE_NAME = 'pocketlists_list' AND COLUMN_NAME = 'sort'
    ")->fetchField('DATA_TYPE');
    if ($sort_type == 'int') {
        $model->exec("
            ALTER TABLE pocketlists_list MODIFY COLUMN sort VARCHAR(32) DEFAULT '0' NOT NULL
        ");
    }
} catch (waDbException $wdb_ex) {
}
