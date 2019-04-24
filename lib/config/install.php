<?php

/** @var pocketlistsPocketFactory $pocketFactory */
$pocketFactory = pl2()->getEntityFactory(pocketlistsPocket::class);

/** @var pocketlistsPocket $pocket */
$pocket = $pocketFactory->createNew();
$pocket->setName(wa()->accountName() ? wa()->accountName() : _w('Pocket Lists'));

$pocketFactory->insert($pocket);

$pr = new pocketlistsRightConfig();
$pr->setRights(wa()->getUser()->getId(), 'backend', 2);

$linkedAppPath = wa()->getAppPath('lib/config/linked_apps.php', pocketlistsHelper::APP_ID);

if (!file_exists($linkedAppPath)) {
    $conf = [];
    try {
        if (wa()->appExists('shop')) {
            $conf['shop'] = ['enable' => 1];
        }
    } catch (waException $ex) {
        waLog::log(sprintf('pocketlsits install: $s', $ex->getMessage()));
    }

    waUtils::varExportToFile($conf, $linkedAppPath);
}

$m = new pocketlistsModel();

$updates = require wa()->getAppPath('lib/config/utf8mb4.php', pocketlistsHelper::APP_ID);

try {
    foreach ($updates as $table => $columns) {
        foreach ($columns as $column => $type) {
            $m->exec(
                sprintf(
                    'alter table %s change %s %s %s character set utf8mb4 collate utf8mb4_unicode_ci',
                    $table,
                    $column,
                    $column,
                    $type
                )
            );
        }
    }
} catch (Exception $ex) {
    waLog::log('Error on altering to utf8mb4.', 'pocketlists/update.log');
}
