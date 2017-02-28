<?php
// pocketsbye update

// rights "pockets -> lists"
// get all pockets lists and add'em to rights
$rights_model = new waContactRightsModel();
$model = new waModel();

$current_pocket_rights = $rights_model->select("*")->where("app_id = 'pocketlists'")->fetchAll();
foreach ($current_pocket_rights as $current_pocket_right) {
    $pocket_right = $current_pocket_right['name'];
    // if user is admin - nothing to change
    // else - change pocket to list and add lists ids
    $pocket_id = explode('.', $pocket_right);
    if (is_array($pocket_id) && !empty($pocket_id[1])) {
        $pocket_id = $pocket_id[1];
        $lists = $model->query("SELECT * FROM pocketlists_list WHERE pocket_id = {$pocket_id}");
        foreach ($lists as $list) {
            $rights_model->insert(array(
                'group_id' => $current_pocket_right['group_id'],
                'app_id'   => $current_pocket_right['app_id'],
                'name'     => 'list.' . $list['id'],
                'value'    => $current_pocket_right['value'],
            ));
        }

        $rights_model->deleteByField(array(
            'group_id' => $current_pocket_right['group_id'],
            'app_id'   => $current_pocket_right['app_id'],
            'name'     => $current_pocket_right['name'],
            'value'    => $current_pocket_right['value'],
        ));
    }
}