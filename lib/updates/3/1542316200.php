<?php

$m = new waModel();

$m->exec("ALTER TABLE `pocketlists_item_link` CHANGE `entity_id` `entity_id` INT(11)  NULL");
