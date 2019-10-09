<?php

$m = new pocketlistsModel();

try {
    $m->query('select execution_count from pocketlists_pro_automation');
} catch (waException $ex) {
    $m->query('alter table pocketlists_pro_automation add execution_count int null');
    $m->query('alter table pocketlists_pro_automation add last_execution_datetime datetime null');
    $m->query('drop table pocketlists_pro_automation_rules');
}
