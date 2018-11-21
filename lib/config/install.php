<?php

(new pocketlistsPocketModel(
    [
        'name' => wa()->accountName() ? wa()->accountName() : _w('Pocket Lists'),
    ]
))->save();

$pr = new pocketlistsRightConfig();
$pr->setRights(wa()->getUser()->getId(), 'backend', 2);
