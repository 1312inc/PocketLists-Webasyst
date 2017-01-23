<?php
// pocketsbye update
$model = new waModel();

try {
    if ($model->exec("SELECT pocket_id FROM pocketlists_list")) {
        $model->exec('ALTER TABLE pocketlists_list DROP COLUMN pocket_id');
    }
} catch (waException $ex) {}

try {
    $model->exec('DROP TABLE pocketlists_pocket');
} catch (waException $ex) {}
try {
    $model->exec('DROP TABLE pocketlists_pocket_rights');
} catch (waException $ex) {}