<?php
return array(
    'pocketlists_pro_automation' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'event' => array('text', 'null' => 0),
        'rules' => array('text', 'null' => 0),
        'action' => array('text', 'null' => 0),
        'type' => array('enum', "'shop'", 'null' => 0),
        'created_by' => array('int', 11, 'null' => 0),
        'created_datetime' => array('datetime'),
        'updated_datetime' => array('datetime'),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'pocketlists_pro_automation_rules' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'conditions' => array('text', 'null' => 0),
        'created_by' => array('int', 11, 'null' => 0),
        'created_datetime' => array('datetime'),
        'updated_datetime' => array('datetime'),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
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
