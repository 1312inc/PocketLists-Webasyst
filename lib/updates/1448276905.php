<?php

$pm = new pocketlistsPocketModel();
$pm->exec("ALTER TABLE `pocketlists_pocket` CHANGE `color` `color` ENUM('none','red','green','blue','yellow','purple')  CHARACTER SET utf8  COLLATE utf8_general_ci  NULL  DEFAULT 'none';
");

$pm->insert(array(
    'id' => 1,
    'name' => 'Personal'
), 2);