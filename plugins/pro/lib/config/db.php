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
        'execution_count' => array('int', 11),
        'last_execution_datetime' => array('datetime'),
        'enabled' => array('tinyint', 3, 'unsigned' => 1, 'default' => '1'),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'pocketlists_pro_delayed_automation' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'automation_id' => array('int', 11, 'null' => 0),
        'status' => array('smallint', 6, 'null' => 0, 'default' => '0'),
        'event_data' => array('text', 'null' => 0),
        'apply_datetime' => array('datetime', 'null' => 0),
        'created_datetime' => array('datetime', 'null' => 0),
        'executed_datetime' => array('datetime'),
        'error' => array('text'),
        'item_id' => array('int', 11),
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
