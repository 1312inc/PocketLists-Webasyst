<?php
// let's update wa_log json data (we should save only ids, not raw data)

$m = new pocketlistsWaLogModel();

try {
    $logs = $m->getAllLogs();
    foreach ($logs as $log) {
        if (in_array($log['action'], array(pocketlistsLogAction::LIST_CREATED, pocketlistsLogAction::NEW_ITEMS))) {
            $data = json_decode($log['params'], true);
            if ($data && isset($data['id'])) {
                $data = array(
                    'list_id' => $data['id'],
                );
            }
        }
        if (in_array($log['action'], array(pocketlistsLogAction::ITEM_COMPLETED))) {
            $data = json_decode($log['params'], true);
            $im = new pocketlistsItemModel();
            if ($data && isset($data['id'])) {
                $data = array(
                    'item_id' => $data['id'],
                );
            }
        }

        if (in_array($log['action'], array(pocketlistsLogAction::LIST_CREATED, pocketlistsLogAction::NEW_ITEMS, pocketlistsLogAction::ITEM_COMPLETED)) && $data) {
            $m->updateById($log['id'], array(
                'params' => json_encode($data)
            ));
        }
    }
} catch (waException $ex) {

}