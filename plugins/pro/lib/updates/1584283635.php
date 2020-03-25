<?php
$m = new waModel();

try {
    $m->exec('select enabled from pocketlists_pro_automation');
} catch (Exception $exception) {
    $m->exec('alter table pocketlists_pro_automation add enabled tinyint unsigned default 1');
}
