<?php
return array(
    'pocketlists_attachment' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'item_id' => array('int', 11, 'null' => 0),
        'filename' => array('text'),
        'filetype' => array('enum', "'image'"),
        ':keys' => array(
            'PRIMARY' => 'id',
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
            'item_id' => 'item_id',
            'contact_id' => 'contact_id',
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
            'key_list_id' => 'key_list_id',
            'complete_contact_id' => 'complete_contact_id',
            'contact_id' => 'contact_id',
            'assigned_contact_id' => 'assigned_contact_id',
        ),
    ),
    'pocketlists_item_link' => array(
        'id' => array('int', 11, 'unsigned' => 1, 'null' => 0, 'autoincrement' => 1),
        'item_id' => array('int', 11, 'null' => 0),
        'app' => array('varchar', 50, 'null' => 0, 'default' => ''),
        'entity_type' => array('varchar', 50, 'null' => 0, 'default' => ''),
        'entity_id' => array('int', 11),
        'data' => array('text'),
        ':keys' => array(
            'PRIMARY' => 'id',
            'item_id_2' => array('item_id', 'app', 'entity_type', 'entity_id', 'unique' => 1),
            'item_id' => 'item_id',
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
    'pocketlists_log' => array(
        'id' => array('bigint', 20, 'null' => 0, 'autoincrement' => 1),
        'action' => array('varchar', 30, 'null' => 0),
        'entity_type' => array('varchar', 30),
        'contact_id' => array('int', 11),
        'pocket_id' => array('int', 11),
        'list_id' => array('int', 11),
        'item_id' => array('int', 11),
        'comment_id' => array('int', 11),
        'attachment_id' => array('int', 11),
        'location_id' => array('int', 11),
        'additional_id' => array('int', 11),
        'assigned_contact_id' => array('int', 11),
        'params' => array('text'),
        'create_datetime' => array('datetime'),
        ':keys' => array(
            'PRIMARY' => 'id',
            'pocketlists_log_action_index' => 'action',
            'pocketlists_log_contact_id_index' => 'contact_id',
            'pocketlists_log_item_id_index' => 'item_id',
            'pocketlists_log_list_id_index_2' => 'list_id',
            'pocketlists_log_pocket_id_index' => 'pocket_id',
        ),
    ),
    'pocketlists_notification' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'type' => array('varchar', 30, 'null' => 0),
        'identifier' => array('varchar', 64),
        'handler' => array('text'),
        'created_at' => array('datetime', 'null' => 0),
        'delayed_to' => array('datetime'),
        'sent_at' => array('datetime'),
        'status' => array('varchar', 255),
        'error' => array('text'),
        'data' => array('text'),
        'contact_id' => array('int', 11),
        'direction' => array('varchar', 10, 'default' => 'external'),
        ':keys' => array(
            'PRIMARY' => 'id',
            'pocketlists_notification_status_index' => 'status',
        ),
    ),
    'pocketlists_pocket' => array(
        'id' => array('int', 11, 'unsigned' => 1, 'null' => 0, 'autoincrement' => 1),
        'sort' => array('int', 11, 'null' => 0, 'default' => '0'),
        'name' => array('varchar', 200),
        'color' => array('enum', "'none','red','green','blue','yellow','purple'", 'null' => 0, 'default' => 'blue'),
        'passcode' => array('varchar', 32),
        ':keys' => array(
            'PRIMARY' => 'id',
            'sort' => 'sort',
        ),
    ),
    'pocketlists_tag' => array(
        'id' => array('int', 11, 'null' => 0, 'autoincrement' => 1),
        'text' => array('varchar', 200, 'null' => 0),
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
