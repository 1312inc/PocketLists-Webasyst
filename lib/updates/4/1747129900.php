<?php

$model = new pocketlistsModel();

try {
    $model->exec("DELETE FROM wa_contact_settings WHERE app_id = 'pocketlists' AND name = 'app_icon'");
} catch (waDbException $wdb_ex) {
}
