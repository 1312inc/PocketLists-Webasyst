<?php

return [
    pocketlistsEventStorage::ITEM_UI_LABEL => ['pocketlistsProPluginItemEventListener', 'getLabel'],
    pocketlistsEventStorage::ITEM_INSERT   => ['pocketlistsProPluginItemEventListener', 'onInsert'],
    pocketlistsEventStorage::ITEM_UPDATE   => ['pocketlistsProPluginItemEventListener', 'onUpdate'],
];
