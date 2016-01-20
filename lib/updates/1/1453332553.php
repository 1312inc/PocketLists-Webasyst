<?php
$m = new waModel();

try {
    $m->query('SELECT sort FROM pocketlists_pocket');
} catch (waDbException $e) {
    $m->query(
        "ALTER TABLE pocketlists_pocket ADD sort INT(11) NOT NULL DEFAULT 0 AFTER id"
    );
}