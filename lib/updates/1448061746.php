<?php
// adding sort field to pocketlists_item

$model = new waModel();

$model->query("ALTER TABLE `pocketlists_list` CHANGE `color` `color` ENUM('none','red','green','blue','yellow','purple')  CHARACTER SET utf8  COLLATE utf8_general_ci NULL  DEFAULT 'none';");
