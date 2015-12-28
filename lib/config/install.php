<?php
$pm = new pocketlistsPocketModel();
$inserted = $pm->insert(array(
    'name' => wa()->accountName()
));
$pr = new pocketlistsRightConfig();
$pr->setRights(wa()->getUser()->getId(), 'backend', 2);
