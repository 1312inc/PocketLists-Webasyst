<?php

$model = new pocketlistsModel();

$model->createSchema([
    'pocketlists_push_client' => [
        'client_id' => ['varchar', 255, 'null' => 0],
        'contact_id' => ['int', 11, 'null' => 0],
        'api_token' => ['varchar', 32],
        'create_datetime' => ['datetime', 'null' => 0],
        ':keys' => [
            'PRIMARY' => 'client_id',
            'pocketlists_push_client_client_id_uindex' => ['client_id', 'unique' => 1],
            'pocketlists_push_client_contact_id_index' => 'contact_id',
        ]
    ]
]);
