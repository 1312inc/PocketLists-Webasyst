<?php
// add key_list_id

$model = new waModel();

try {
    $model->exec("SELECT key_list_id FROM pocketlists_item");
} catch (waDbException $e) {
    $model->exec("ALTER TABLE pocketlists_item ADD key_list_id INT(11) NULL ");

    // for each list add hidden item
    $lm = new pocketlistsListModel();
    $im = new pocketlistsItemModel();
    foreach ($lm->getAll() as $list) {
        $inserted = $im->insert(
            array(
                'contact_id' => $list['contact_id'],
                'name' => $list['name'],
                'create_datetime' => $list['create_datetime'],
                'update_datetime' => $list['update_datetime'],
                'key_list_id' => $list['id'],
            )
        );
        $lm->updateById($list['id'], array('key_item_id' => $inserted));
    }
    $model->exec("ALTER TABLE pocketlists_list DROP contact_id");
    $model->exec("ALTER TABLE pocketlists_list DROP name");
    $model->exec("ALTER TABLE pocketlists_list DROP create_datetime");
    $model->exec("ALTER TABLE pocketlists_list DROP update_datetime");
}
