<?php

/** @var pocketlistsPocketFactory $pocketFactory */
$pocketFactory = pl2()->getEntityFactory(pocketlistsPocket::class);

/** @var pocketlistsPocket $pocket */
$pocket = $pocketFactory->createNew();
$pocket->setName(wa()->accountName() ? wa()->accountName() : _w('Pocket Lists'));

$pocketFactory->insert($pocket);

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
