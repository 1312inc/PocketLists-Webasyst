<?php

$m = new waModel();

try {
    $m->exec("select * from pocketlists_item_link");
} catch (waException $ex) {
    $m->exec(
        "CREATE TABLE `pocketlists_item_link` (
          `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
          `item_id` int(11) NOT NULL,
          `app` varchar(50) NOT NULL DEFAULT '',
          `entity_type` varchar(50) NOT NULL DEFAULT '',
          `entity_id` int(11) NOT NULL,
          `data` text,
          PRIMARY KEY (`id`),
          KEY `item_id` (`item_id`)
        ) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
    );
}
