<?php

return [
    pocketlistsEventStorage::ITEM_SAVE   => ['pocketlistsProPluginItemEventListener', 'onSave'],
    pocketlistsEventStorage::ITEM_DELETE => ['pocketlistsProPluginItemEventListener', 'onDelete'],
    pocketlistsEventStorage::ITEM_UI_LABEL => ['pocketlistsProPluginItemEventListener', 'getLabel']
];
