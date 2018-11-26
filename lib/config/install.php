<?php

(new pocketlistsPocketModel(
    [
        'name' => wa()->accountName() ? wa()->accountName() : _w('Pocket Lists'),
    ]
))->save();

$pr = new pocketlistsRightConfig();
$pr->setRights(wa()->getUser()->getId(), 'backend', 2);

$linkedAppPath = wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedAppConfigPath();

if (!file_exists($linkedAppPath)) {
    $conf = [];
    try {
        if (wa()->appExists('shop')) {
            $conf['shop'] = ['enable' => 1];
        }
    } catch (waException $ex) {
        waLog::log(sprintf('pocketlsits install: $s', $ex->getMessage()));
    }

    waUtils::varExportToFile($conf, wa()->getConfig()->getLinkedAppConfigPath());
}
