<?php
$m = new waModel();

try {
    $m->query('SELECT sort FROM pocketlists_list');
} catch (waDbException $e) {
    $m->query(
        "ALTER TABLE pocketlists_list ADD sort INT(11) NOT NULL DEFAULT 0 AFTER pocket_id"
    );
}