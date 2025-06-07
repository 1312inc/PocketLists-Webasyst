<?php

$app_settings = new waAppSettingsModel();
$app_settings->del(pocketlistsHelper::APP_ID, 'install_hash');
