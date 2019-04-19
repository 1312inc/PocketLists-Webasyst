<?php

$rightsModel = new waContactRightsModel();
$model = new waModel();

$currentRights = $rightsModel->query(
    "select * from {$rightsModel->getTableName()} where app_id = 'pocketlists'"
)->fetchAll();

$pockets = (new pocketlistsPocketModel())->getAll('id');

foreach ($currentRights as $rights) {
    if (in_array($rights['value'], [pocketlistsRBAC::RIGHT_ADMIN, pocketlistsRBAC::RIGHT_LIMITED])) {
        continue;
    }

    foreach ($pockets as $pocket) {
        try {
            $rightsModel->insert(
                [
                    'group_id' => $rights['group_id'],
                    'app_id'   => pocketlistsHelper::APP_ID,
                    'name'     => pocketlistsRBAC::POCKET_ITEM.'.'.$pocket['id'],
                    'value'    => pocketlistsRBAC::RIGHT_LIMITED,
                ]
            );
        } catch (waException $ex) {
            waLog::log(
                sprintf(
                    'Error on updating group %s rights. Maybe it was updated before.',
                    $rights['group_id']
                ),
                'pocketlists/update.log'
            );
        }
    }
}