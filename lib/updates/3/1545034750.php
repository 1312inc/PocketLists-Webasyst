<?php

$rightsModel = new waContactRightsModel();
$model = new waModel();

$currentPocketRights = $rightsModel->query(
    "select * from {$rightsModel->getTableName()} where app_id = 'pocketlists'"
)->fetchAll('name', 2);

if (isset($currentPocketRights[pocketlistsRBAC::CAN_ASSIGN])) {
    foreach ($currentPocketRights[pocketlistsRBAC::CAN_ASSIGN] as $contactWithRights) {
        try {
            $rightsModel->insert(
                [
                    'group_id' => $contactWithRights['group_id'],
                    'app_id'   => $contactWithRights['app_id'],
                    'name'     => pocketlistsRBAC::CAN_USE_SHOP_SCRIPT,
                    'value'    => $contactWithRights['value'],
                ]
            );
        } catch (waException $ex) {
            waLog::log(
                sprintf(
                    'Error on updating group %s rights. Maybe it was updated before.',
                    $contactWithRights['group_id']
                ),
                'pocketlists/update.log'
            );
        }
    }
}