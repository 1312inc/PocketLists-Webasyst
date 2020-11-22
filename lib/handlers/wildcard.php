<?php

return [
    [
        'event_app_id' => 'contacts',
        'event' => 'profile.tab',
        'class' => 'pocketlistsContactsProfileTab',
        'method' => ['execute'],
    ],
    [
        'event_app_id' => 'shop',
        'event' => 'backend_order',
        'class' => 'pocketlistsShopBackendOrder',
        'method' => ['execute'],
    ],
    [
        'event_app_id' => 'shop',
        'event' => 'backend_orders',
        'class' => 'pocketlistsShopBackendOrders',
        'method' => ['execute'],
    ],
    [
        'event_app_id' => 'webasyst',
        'event' => 'backend_header',
        'class' => 'pocketlistsWebasystBackendHeader',
        'method' => ['execute'],
    ],
    [
        'event_app_id' => 'tasks',
        'event' => 'backend_task',
        'class' => 'pocketlistsTasksBackendTask',
        'method' => ['execute'],
    ],
    [
        'event_app_id' => 'tasks',
        'event' => 'backend_tasks',
        'class' => 'pocketlistsTasksBackendTasks',
        'method' => ['execute'],
    ],
];
