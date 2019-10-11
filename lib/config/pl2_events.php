<?php

return [
    pocketlistsEventStorage::ITEM_INSERT => [
        ['pocketlistsEventListenerItemSave', 'notifyAndLogAboutNew'],
        ['pocketlistsEventListenerItemSave', 'notifyAndLogAboutNewAssign'],
    ],
    pocketlistsEventStorage::ITEM_UPDATE => [
        ['pocketlistsEventListenerItemSave', 'notificationAndLog'],
        ['pocketlistsEventListenerItemSave', 'logAboutUpdated'],
    ]
];
