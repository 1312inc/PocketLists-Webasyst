<?php

$m = new waModel();

$m->exec("ALTER TABLE `pocketlists_item_link` ADD UNIQUE INDEX (`item_id`, `app`, `entity_type`, `entity_id`)");
