<?php

pocketlistsHelper::updateUtf8Mb(wa()->getAppPath('plugins/pro/lib/config/utf8mb4.php', pocketlistsHelper::APP_ID));

$m = new pocketlistsModel();

$m->exec("alter table pocketlists_item add pro_label_id int default null");

$labels = [
    [_wp('Backlog'), 'aaaaaa'],
    [_wp('Meeting'), '31e074'],
    [_wp('Docs'), 'd180f5'],
    [_wp('Shipment'), '5d96ff'],
    [_wp('Bill'), 'faca2e'],
    [_wp('Call'), 'f27c44'],
    [_wp('Reminder'), '74d5fb'],
];

foreach ($labels as $label) {
    $m->exec(
        sprintf("insert into pocketlists_pro_label (name, color, sort) values ('%s', '%s', null)", $label[0], $label[1])
    );
}

$shortcuts = [
    [_wp('Check'), _wp('Reach out'), _wp('Pay'), _wp('Ship'), _wp('Order'), _wp('Prepare'), _wp('Return')],
    [_wp('today'), _wp('tomorrow'), _wp('in 2 days'), _wp('in 3 days'), _wp('in a week'), _wp('in 2 weeks'), _wp('in a month')],
];

foreach ($shortcuts as $i => $names) {
    foreach ($names as $name) {
        $m->exec(
            sprintf("insert into pocketlists_pro_shortcut (name, `group`) values ('%s', %d)", $name, $i + 1)
        );
    }
}
