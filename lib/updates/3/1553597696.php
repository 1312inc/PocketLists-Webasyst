<?php

try {
    (new waModel())->exec("ALTER TABLE `pocketlists_item` ADD INDEX (`key_list_id`)");
} catch (waException $ex) {
    waLog::log('Error on adding index to pocketlists_item.', 'pocketlists/update.log');
}
