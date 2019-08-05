<?php
return array(
    'pocketlists_pro_label' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'name' => array('mediumtext'),
        'color' => array('varchar', 6, 'null' => 0),
        'sort' => array('mediumint', 9),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'pocketlists_pro_shortcut' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'name' => array('varchar', 200, 'null' => 0),
        'group' => array('tinyint', 4, 'null' => 0, 'default' => '1'),
        ':keys' => array(
            'PRIMARY' => 'id',
            'pocketlists_pro_shortcut_group_name_uindex' => array('group', 'name', 'unique' => 1),
        ),
    ),
);
