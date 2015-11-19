<?php
$model = new waModel();

$model->exec("ALTER TABLE `pocketlists_item` CHANGE `name` `name` MEDIUMTEXT CHARACTER SET utf8 COLLATE utf8_general_ci NULL");