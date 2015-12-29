<?php
// add priority field

$model = new waModel();

try {
    $model->query("SELECT `priority` FROM pocketlists_item");
} catch (waDbException $e) {
    $model->query(
        "ALTER TABLE pocketlists_item ADD `priority` TINYINT(1) NOT NULL DEFAULT 0 COMMENT '0 (none), 1 (green), 2 (yrllow), 3 (red)' AFTER `status`"
    );

    $model->query(
        "ALTER TABLE `pocketlists_item` CHANGE `status` `status` TINYINT(1) NOT NULL DEFAULT '0' COMMENT '0 — undone, 1 — done, -1 — archived (archive ставится для всех айтемов списка при добавлении списка в архив; восстановление из архива, разумеется, возможно только в undone; при поиске айтемов и фильтрации зато можно будет смотреть только на status >= 0)'"
    );
}
