<?php

$linkedAppPath = wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedAppConfigPath();

if (!file_exists($linkedAppPath)) {
    $conf = [];
    try {
        if (wa()->appExists('shop')) {
            $conf['shop'] = ['enable' => 1];
        }
    } catch (waException $ex) {
        waLog::log(sprintf('pocketlsits update: $s', $ex->getMessage()));
    }

    waUtils::varExportToFile($conf, wa()->getConfig()->getLinkedAppConfigPath());
}