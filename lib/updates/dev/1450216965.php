<?php

$model = new waModel();

$model->exec("ALTER TABLE pocketlists_item CHANGE due_datetime due_datetime DATETIME NULL
COMMENT 'если указано точное время напоминания';");
