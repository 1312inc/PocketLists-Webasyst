<?php
return [
    'name'     => 'Pocket Lists PRO',
    'img'      => 'img/pl2pro.png',
    'version'  => '1.0.0',
    'vendor'   => '--',
    'handlers' =>
        [
            '*' => [
                [
                    'event_app_id' => 'pocketlists',
                    'event'        => 'entity_insert.before',
                    'class'        => 'pocketlistsProPluginWaEventListener',
                    'method'       => 'onEntityInsertBefore',
                ],
                [
                    'event_app_id' => 'pocketlists',
                    'event'        => 'entity_update.before',
                    'class'        => 'pocketlistsProPluginWaEventListener',
                    'method'       => 'onEntityUpdateBefore',
                ],
                [
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_head',
                    'class'        => 'pocketlistsProPluginHookHandlerHead',
                    'method'       => 'handle',
                ],
                [
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_settings',
                    'class'        => 'pocketlistsProPluginHookHandlerSettings',
                    'method'       => 'handle',
                ],
                [
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_sidebar',
                    'class'        => 'pocketlistsProPluginHookHandlerSidebar',
                    'method'       => 'handle',
                ],
                [
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_pocket',
                    'class'        => 'pocketlistsProPluginHookHandlerPocket',
                    'method'       => 'handle',
                ],
                [
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_item_add',
                    'class'        => 'pocketlistsProPluginHookHandlerItemAdd',
                    'method'       => 'handle',
                ],
                [
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_item_add_external',
                    'class'        => 'pocketlistsProPluginHookHandlerItemAdd',
                    'method'       => 'external',
                ],
                [
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_list_accesses',
                    'class'        => 'pocketlistsProPluginHookHandlerListAccesses',
                    'method'       => 'handle',
                ],
                [
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_teammate_sidebar',
                    'class'        => 'pocketlistsProPluginHookHandlerTeammateSidebar',
                    'method'       => 'handle',
                ],
            ],
        ],
];
