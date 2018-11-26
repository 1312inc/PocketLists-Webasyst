<?php

$m = new waModel();

try {
    $m->exec("select pocket_id from pocketlists_list");
} catch (waException $ex) {
    $m->exec(
        "ALTER TABLE pocketlists_list ADD pocket_id INT(11) NOT NULL DEFAULT 0 AFTER id"
    );
}
