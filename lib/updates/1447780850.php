<?php
// adding sort field to pocketlists_item

$model = new waModel();

try {
    $model->query("SELECT `sort` FROM pocketlists_item");
} catch (waDbException $e) {
    $model->query("ALTER TABLE pocketlists_item ADD `sort` INT(11) NOT NULL DEFAULT 0 AFTER parent_id");
    $model->query("ALTER TABLE pocketlists_item ADD INDEX sort (parent_id, sort)");
}