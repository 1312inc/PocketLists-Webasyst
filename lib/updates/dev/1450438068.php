<?php
$cs = new waContactSettingsModel();
$app_name = 'pocketlists';
$cs->set(wa()->getUser()->getId(), $app_name, 'stream_inbox_list', 0);
