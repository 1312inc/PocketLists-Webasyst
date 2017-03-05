<?php
$lm = new pocketlistsListModel();
$lm->add(array(
    'name'            => wa()->accountName(),
    'type'            => 'checklist',
    'create_datetime' => date('Y-m-d H:i:s'),
    'contact_id'      => wa()->getUser()->getId(),
));
$pr = new pocketlistsRightConfig();
$pr->setRights(wa()->getUser()->getId(), 'backend', 2);
