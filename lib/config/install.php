<?php

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
        waLog::log(sprintf('pocketlsits install: %s', $ex->getMessage()));
    }

    waUtils::varExportToFile($conf, $linkedAppPath);
}

// Setup auto thumbnail generation for pocketlists image attachments
$path = wa()->getDataPath('attachments', true, pocketlistsHelper::APP_ID);
waFiles::write($path.'/thumb.php', '<?php
$file = dirname(__FILE__)."/../../../../"."/wa-apps/'.pocketlistsHelper::APP_ID.'/lib/config/data/thumb.php";

if (file_exists($file)) {
    include($file);
} else {
    header("HTTP/1.0 404 Not Found");
}
');
waFiles::copy(wa()->getAppPath('lib/config/data/.htaccess', pocketlistsHelper::APP_ID), $path.'/.htaccess');

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


$labels = [
    [_w('Backlog'), 'aaaaaa'],
    [_w('Meeting'), '31e074'],
    [_w('Docs'), 'd180f5'],
    [_w('Shipment'), '5d96ff'],
    [_w('Bill'), 'faca2e'],
    [_w('Call'), 'f27c44'],
    [_w('Reminder'), '74d5fb'],
];

foreach ($labels as $label) {
    $m->exec(sprintf("insert into pocketlists_pro_label (name, color, sort) values ('%s', '%s', null)", $label[0], $label[1]));
}

$shortcuts = [
    [
        _w('Check'),
        _w('Reach out'),
        _w('Pay'),
        _w('Ship'),
        _w('Order'),
        _w('Prepare'),
        _w('Return')
    ], [
        _w('today'),
        _w('tomorrow'),
        _w('in 2 days'),
        _w('in 3 days'),
        _w('in a week'),
        _w('in 2 weeks'),
        _w('in a month')
    ],
];

foreach ($shortcuts as $i => $names) {
    foreach ($names as $name) {
        $m->exec(sprintf("insert into pocketlists_pro_shortcut (name, `group`) values ('%s', %d)", $name, $i + 1));
    }
}
