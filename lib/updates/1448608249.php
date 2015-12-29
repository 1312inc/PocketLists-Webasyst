<?php

$model = new waModel();

$model->exec("ALTER TABLE `pocketlists_list` CHANGE `icon` `icon` VARCHAR(64)  CHARACTER SET utf8
COLLATE utf8_general_ci  NULL  DEFAULT NULL  COMMENT 'текстовый идентификатор иконки';");
