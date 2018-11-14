<?php

(new waModel())->exec("ALTER TABLE `pocketlists_list` ADD INDEX (`pocket_id`)");
