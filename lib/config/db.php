<?php
return array(
    'pocketlists_attachment' => array(
        'item_id' => array('int', 11, 'null' => 0),
        'filename' => array('text'),
        'filetype' => array('enum', "'image'"),
        ':keys' => array(
            'item_id' => 'item_id',
        ),
    ),
    'pocketlists_comment' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'item_id' => array('int', 11),
        'contact_id' => array('int', 11),
        'comment' => array('text'),
        'create_datetime' => array('datetime'),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'pocketlists_item' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'list_id' => array('int', 11),
        'contact_id' => array('int', 11),
        'parent_id' => array('int', 11, 'null' => 0, 'default' => '0'),
        'sort' => array('int', 11, 'null' => 0, 'default' => '0'),
        'has_children' => array('tinyint', 1, 'null' => 0, 'default' => '0'),
        'status' => array('tinyint', 1, 'null' => 0, 'default' => '0'),
        'priority' => array('tinyint', 1, 'null' => 0, 'default' => '0'),
        'calc_priority' => array('tinyint', 1, 'null' => 0, 'default' => '0'),
        'create_datetime' => array('datetime'),
        'update_datetime' => array('datetime'),
        'complete_datetime' => array('datetime'),
        'complete_contact_id' => array('int', 11),
        'name' => array('mediumtext'),
        'note' => array('text'),
        'due_date' => array('date'),
        'due_datetime' => array('datetime'),
        'location_id' => array('int', 11),
        'amount' => array('decimal', "10,0"),
        'currency_iso3' => array('varchar', 3),
        'assigned_contact_id' => array('int', 11),
        'repeat' => array('int', 11),
        'key_list_id' => array('int', 11),
        ':keys' => array(
            'PRIMARY' => 'id',
            'parent' => 'parent_id',
            'list_id' => 'list_id',
            'sort' => array('parent_id', 'sort'),
        ),
    ),
    'pocketlists_item_sort' => array(
        'list_id' => array('int', 11, 'null' => 0),
        'item_id' => array('int', 11, 'null' => 0),
        'sort' => array('int', 11),
        ':keys' => array(
            'PRIMARY' => array('list_id', 'item_id'),
        ),
    ),
    'pocketlists_item_tags' => array(
        'item_id' => array('int', 11, 'null' => 0),
        'tag_id' => array('int', 11, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => array('item_id', 'tag_id'),
        ),
    ),
    'pocketlists_list' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'pocket_id' => array('int', 11, 'null' => 0, 'default' => '0'),
        'sort' => array('int', 11, 'null' => 0, 'default' => '0'),
        'type' => array('enum', "'checklist','notes'"),
        'icon' => array('varchar', 64),
        'archived' => array('tinyint', 1, 'default' => '0'),
        'hash' => array('varchar', 32),
        'color' => array('enum', "'none','red','green','blue','yellow','purple'", 'default' => 'none'),
        'passcode' => array('varchar', 32),
        'key_item_id' => array('int', 11),
        ':keys' => array(
            'PRIMARY' => 'id',
            'pocket_id' => 'pocket_id',
        ),
    ),
    'pocketlists_list_sort' => array(
        'list_id' => array('int', 11, 'null' => 0),
        'contact_id' => array('int', 11, 'null' => 0),
        'sort' => array('int', 11),
        ':keys' => array(
            'PRIMARY' => array('list_id', 'contact_id'),
        ),
    ),
    'pocketlists_location' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'location_latitude' => array('decimal', "10,0"),
        'location_longitude' => array('decimal', "10,0"),
        'location_radius' => array('decimal', "10,0"),
        ':keys' => array(
            'PRIMARY' => 'id',
        ),
    ),
    'pocketlists_pocket' => array(
        'id' => array('int', 11, 'unsigned' => 1, 'null' => 0, 'autoincrement' => 1),
        'sort' => array('int', 11, 'null' => 0, 'default' => '0'),
        'name' => array('varchar', 255, 'null' => 0, 'default' => ''),
        'color' => array('enum', "'none','red','green','blue','yellow','purple'", 'null' => 0, 'default' => 'blue'),
        'passcode' => array('varchar', 32),
        ':keys' => array(
            'PRIMARY' => 'id',
            'sort' => 'sort',
        ),
    ),
    'pocketlists_tag' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'text' => array('varchar', 255, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => array('id', 'text'),
        ),
    ),
    'pocketlists_user_favorites' => array(
        'item_id' => array('int', 11, 'null' => 0),
        'contact_id' => array('int', 11, 'null' => 0),
        ':keys' => array(
            'PRIMARY' => array('item_id', 'contact_id'),
        ),
    ),
);
