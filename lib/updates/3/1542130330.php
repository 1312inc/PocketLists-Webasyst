<?php

$m = new waModel();

try {
    $m->exec("select * from pocketlists_pocket");
} catch (waException $ex) {
    $m->exec(
        "CREATE TABLE `pocketlists_pocket` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `sort` int(11) NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL DEFAULT '',
  `color` enum('none','red','green','blue','yellow','purple') NOT NULL DEFAULT 'blue',
  `passcode` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
    );
}
