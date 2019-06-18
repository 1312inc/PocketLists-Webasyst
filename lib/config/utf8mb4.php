<?php

return [
    'pocketlists_item'         => [
        'name' => 'mediumtext',
        'note' => 'text',
    ],
    'pocketlists_tag'          => [
        'text' => 'varchar(200)',
    ],
    'pocketlists_pocket'       => [
        'name' => 'varchar(200)',
    ],
    'pocketlists_attachment'   => [
        'filename' => 'text',
    ],
    'pocketlists_comment'      => [
        'comment' => 'text',
    ],
    'pocketlists_item_link'    => [
        'data' => 'text',
    ],
    'pocketlists_notification' => [
        'error' => 'text',
        'data'  => 'text',
    ],
    'pocketlists_log' => [
        'params' => 'text',
    ],
];
