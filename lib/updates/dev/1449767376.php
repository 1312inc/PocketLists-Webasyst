<?php

$model = new waModel();

try {
    $model->exec("SELECT calc_priority FROM pocketlists_item");
} catch (waDbException $e) {
    $model->exec("ALTER TABLE pocketlists_item ADD calc_priority TINYINT(1) NOT NULL DEFAULT '0' AFTER priority");
}
