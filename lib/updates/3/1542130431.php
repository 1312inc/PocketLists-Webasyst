<?php

$m = new waModel();

$m->exec("ALTER TABLE `pocketlists_item_sort` CHANGE `Item_id` `item_id` INT(11)  NOT NULL");
