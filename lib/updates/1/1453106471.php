<?php
$model = new waModel();

try {
    $model->query("SELECT filetype FROM pocketlists_attachment");
} catch (waDbException $e) {
    $model->query(
        "ALTER TABLE pocketlists_attachment ADD filetype ENUM('image') NULL DEFAULT NULL AFTER filename"
    );
}