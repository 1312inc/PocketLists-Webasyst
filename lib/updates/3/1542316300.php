<?php

$m = new waModel();

$m->exec("ALTER TABLE `pocketlists_item_link` CHANGE `entity_type` `entity_type` VARCHAR (50)  NULL");
