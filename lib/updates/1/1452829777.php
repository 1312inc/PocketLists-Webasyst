<?php
$m = new waModel();

try {
    $m->exec("ALTER TABLE pocketlists_attachment DROP PRIMARY KEY");
    $m->exec("ALTER TABLE pocketlists_attachment ADD INDEX item_id (item_id)");
} catch (waDbException $ex) {

}