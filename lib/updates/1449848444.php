<?php
$wc = new waContactSettingsModel();
$wc->deleteByField(array(
    'contact_id' => wa()->getUser()->getId(),
    'app_id' => 'pocketlists'
));