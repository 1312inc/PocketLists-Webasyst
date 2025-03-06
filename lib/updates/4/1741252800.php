<?php

$model = new pocketlistsModel();

try {
    $model->exec("SELECT pro_label_id FROM pocketlists_item WHERE 0");
} catch (waDbException $wdb_ex) {
    $model->exec("
        ALTER TABLE pocketlists_item ADD pro_label_id INT DEFAULT NULL
    ");
}

try {
    $model->exec("SELECT * FROM pocketlists_pro_automation");
} catch (waDbException $wdb_ex) {
    $model->exec("
        CREATE TABLE `pocketlists_pro_automation` (
            `id` int NOT NULL AUTO_INCREMENT,
            `event` text NOT NULL,
            `rules` text NOT NULL,
            `action` text NOT NULL,
            `type` enum('shop') NOT NULL,
            `created_by` int NOT NULL,
            `created_datetime` datetime DEFAULT NULL,
            `updated_datetime` datetime DEFAULT NULL,
            `execution_count` int DEFAULT NULL,
            `last_execution_datetime` datetime DEFAULT NULL,
            `enabled` tinyint unsigned DEFAULT '1',
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ");
}

try {
    $model->exec("SELECT * FROM pocketlists_pro_delayed_automation");
} catch (waDbException $wdb_ex) {
    $model->exec("
        CREATE TABLE `pocketlists_pro_delayed_automation` (
            `id` int NOT NULL AUTO_INCREMENT,
            `automation_id` int NOT NULL,
            `status` smallint NOT NULL DEFAULT '0',
            `event_data` text NOT NULL,
            `apply_datetime` datetime NOT NULL,
            `created_datetime` datetime NOT NULL,
            `executed_datetime` datetime DEFAULT NULL,
            `error` text,
            `item_id` int DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ");
}

try {
    $model->exec("SELECT * FROM pocketlists_pro_label");
} catch (waDbException $wdb_ex) {
    $model->exec("
        CREATE TABLE `pocketlists_pro_label` (
            `id` int NOT NULL AUTO_INCREMENT,
            `name` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
            `color` varchar(6) NOT NULL,
            `sort` mediumint DEFAULT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ");

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
        $model->exec(sprintf("INSERT INTO pocketlists_pro_label (name, color, sort) VALUES ('%s', '%s', NULL)", $label[0], $label[1]));
    }

}

try {
    $model->exec("SELECT * FROM pocketlists_pro_shortcut");
} catch (waDbException $wdb_ex) {
    $model->exec("
        CREATE TABLE `pocketlists_pro_shortcut` (
            `id` int NOT NULL AUTO_INCREMENT,
            `name` varchar(200) NOT NULL,
            `group` tinyint NOT NULL DEFAULT '1',
            PRIMARY KEY (`id`),
            UNIQUE KEY `pocketlists_pro_shortcut_group_name_uindex` (`group`,`name`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8
    ");

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
            $model->exec(sprintf("INSERT INTO pocketlists_pro_shortcut (name, `group`) VALUES ('%s', %d)", $name, $i + 1));
        }
    }
}
