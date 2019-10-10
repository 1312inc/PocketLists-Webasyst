<?php

/**
 * Class pocketlistsEventStorage
 */
final class pocketlistsEventStorage
{
    const ENTITY_INSERT_BEFORE = 'entity_insert.before';
    const ENTITY_INSERT_AFTER  = 'entity_insert.after';
    const ENTITY_DELETE_BEFORE = 'entity_delete.before';
    const ENTITY_DELETE_AFTER  = 'entity_delete.after';
    const ENTITY_UPDATE_BEFORE = 'entity_update.before';
    const ENTITY_UPDATE_AFTER  = 'entity_update.after';

    const ITEM_SAVE     = 'item_save';
    const ITEM_INSERT   = 'item_insert';
    const ITEM_UPDATE   = 'item_update';
    const ITEM_DELETE   = 'item_delete';
    const ITEM_UI_LABEL = 'item_ui_label';

    const LOG_INSERT  = 'log_insert';

    const NOTIFICATION_SENT = 'notification.sent';

    const WA_BACKEND_HEAD             = 'backend_head';
    const WA_BACKEND_SETTINGS         = 'backend_settings';
    const WA_BACKEND_SIDEBAR          = 'backend_sidebar';
    const WA_BACKEND_POCKET           = 'backend_pocket';
    const WA_BACKEND_ITEM_ADD_COMPACT = 'backend_item_add.compact';
    const WA_BACKEND_ITEM_ADD_DETAIL  = 'backend_item_add.detail';
    const WA_BACKEND_LIST_ACCESSES    = 'backend_list_accesses';
    const WA_BACKEND_TEAMMATE_SIDEBAR = 'backend_teammate_sidebar';
    const WA_ITEM_RENDER_LINKED       = 'item.render_linked';
    const WA_POCKET_DIALOG            = 'backend_pocket_dialog';

    const CREATE_NOTIFICATION_CONTENT = 'create_notification_content';
}
