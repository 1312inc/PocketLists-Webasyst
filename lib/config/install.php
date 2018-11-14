<?php

(new pocketlistsPocketModel(
    [
        'name' => wa()->accountName() ? wa()->accountName() : _w('Pocketlists'),
    ]
))->save();

$pr = new pocketlistsRightConfig();
$pr->setRights(wa()->getUser()->getId(), 'backend', 2);
