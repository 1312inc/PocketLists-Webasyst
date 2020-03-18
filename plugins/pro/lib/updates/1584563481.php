<?php
$m = new waModel();

try {
    $m->exec('select item_id from pocketlists_pro_delayed_automation');
} catch (Exception $exception) {
    $m->exec('alter table pocketlists_pro_delayed_automation add item_id int null');
}
