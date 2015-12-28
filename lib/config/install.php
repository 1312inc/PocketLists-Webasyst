<?php
$pm = new pocketlistsPocketModel();
$inserted = $pm->insert(array(
    'name' => _w('Personal')
));
$pr = new pocketlistsRightConfig();
$pr->setRights(wa()->getUser()->getId(), 'backend', 2);