<?php
$m = new waModel();

$m->exec("DROP TABLE IF EXISTS pocketlists_list_copy");

$m->exec("ALTER TABLE pocketlists_list CHANGE color color ENUM('none','red','green','blue','yellow','purple')  CHARACTER SET utf8  COLLATE utf8_general_ci  NULL  DEFAULT 'none'");
$m->exec("ALTER TABLE pocketlists_pocket CHANGE color color ENUM('none','red','green','blue','yellow','purple')  CHARACTER SET utf8  COLLATE utf8_general_ci  NULL  DEFAULT 'blue'");