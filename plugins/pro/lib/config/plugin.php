<?php
return array(
    'name'     => 'Pocket Lists PRO',
    'img'      => 'img/pl2pro.png',
    'version'  => '2.0.2',
    'vendor'   => '1021997',
    'handlers' =>
        array(
            '*' => array(
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_head',
                    'class'        => 'pocketlistsProPluginHookHandlerHead',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => 'entity_insert.before',
                    'class'        => 'pocketlistsProPluginWaEventListener',
                    'method'       => 'onEntityInsertBefore',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => 'entity_update.before',
                    'class'        => 'pocketlistsProPluginWaEventListener',
                    'method'       => 'onEntityUpdateBefore',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_settings',
                    'class'        => 'pocketlistsProPluginHookHandlerSettings',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_sidebar',
                    'class'        => 'pocketlistsProPluginHookHandlerSidebar',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_pocket',
                    'class'        => 'pocketlistsProPluginHookHandlerPocket',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_item_add.compact',
                    'class'        => 'pocketlistsProPluginHookHandlerItemAdd',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_item_add.detail',
                    'class'        => 'pocketlistsProPluginHookHandlerItemAdd',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_list_accesses',
                    'class'        => 'pocketlistsProPluginHookHandlerListAccesses',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'pocketlists',
                    'event'        => 'backend_teammate_sidebar',
                    'class'        => 'pocketlistsProPluginHookHandlerTeammateSidebar',
                    'method'       => 'handle',
                ),
                array(
                    'event_app_id' => 'shop',
                    'event'        => 'order_action.*',
                    'class'        => 'pocketlistsProPluginWaEventListener',
                    'method'       => 'onOrderAction',
                ),
            ),
        ),
);
