<?php
return array(
    'name'     => 'Pocket Lists PRO',
    'img'      => 'img/pl2pro.png',
    'version'  => '1.0.0',
    'vendor'   => '1021997',
    'handlers' =>
        array(
            '*' => array(
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => pocketlistsEventStorage::WA_BACKEND_HEAD,
                    'class'        => 'pocketlistsProPluginHookHandlerHead',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => pocketlistsEventStorage::ENTITY_INSERT_BEFORE,
                    'class'        => 'pocketlistsProPluginWaEventListener',
                    'method'       => 'onEntityInsertBefore',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => pocketlistsEventStorage::ENTITY_UPDATE_BEFORE,
                    'class'        => 'pocketlistsProPluginWaEventListener',
                    'method'       => 'onEntityUpdateBefore',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => pocketlistsEventStorage::WA_BACKEND_SETTINGS,
                    'class'        => 'pocketlistsProPluginHookHandlerSettings',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => pocketlistsEventStorage::WA_BACKEND_SIDEBAR,
                    'class'        => 'pocketlistsProPluginHookHandlerSidebar',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => pocketlistsEventStorage::WA_BACKEND_POCKET,
                    'class'        => 'pocketlistsProPluginHookHandlerPocket',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => pocketlistsEventStorage::WA_BACKEND_ITEM_ADD_COMPACT,
                    'class'        => 'pocketlistsProPluginHookHandlerItemAdd',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => pocketlistsEventStorage::WA_BACKEND_ITEM_ADD_DETAIL,
                    'class'        => 'pocketlistsProPluginHookHandlerItemAdd',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => pocketlistsEventStorage::WA_BACKEND_LIST_ACCESSES,
                    'class'        => 'pocketlistsProPluginHookHandlerListAccesses',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => pocketlistsEventStorage::WA_BACKEND_TEAMMATE_SIDEBAR,
                    'class'        => 'pocketlistsProPluginHookHandlerTeammateSidebar',
                    'method'       => 'handle',
                ),
            ),
        ),
);
