<?php

$model = new waModel();

try {
    $model->exec("SELECT complete_contact_id FROM pocketlists_item");
} catch (waDbException $e) {
    $model->exec("ALTER TABLE pocketlists_item ADD complete_contact_id INT(11) NULL DEFAULT NULL AFTER complete_datetime");
}