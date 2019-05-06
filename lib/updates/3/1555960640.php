<?php

$rightsModel = new waContactRightsModel();

$currentRights = $rightsModel->query(
    "select * from {$rightsModel->getTableName()} where app_id = 'pocketlists' and `name` like 'pocket.%'"
)->fetchAll();

foreach ($currentRights as $rights) {
    if ($rights['value'] != 2) {
        continue;
    }

    $rightsModel->replace(
        [
            'group_id' => $rights['group_id'],
            'app_id'   => $rights['app_id'],
            'name'     => $rights['name'],
            'value'    => pocketlistsRBAC::RIGHT_ADMIN,
        ]
    );
}
