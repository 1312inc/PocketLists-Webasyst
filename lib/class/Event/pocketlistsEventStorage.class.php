<?php

/**
 * Class pocketlistsEventStorage
 */
final class pocketlistsEventStorage
{
    const ENTITY_INSERT_BEFORE = 'entity_insert.before';
    const ENTITY_INSERT_AFTER  = 'entity_insert.after';
    const ENTITY_DELETE_BEFORE = 'entity_delete.before';
    const ENTITY_UPDATE_BEFORE = 'entity_update.before';

    const ITEM_SAVE     = 'item_save';
    const ITEM_INSERT   = 'item_insert';
    const ITEM_UPDATE   = 'item_update';
    const ITEM_DELETE   = 'item_delete';
    const ITEM_UI_LABEL = 'item_ui_label';

    const LIST_DELETE = 'list_delete';
    const LOG_INSERT = 'lod_insert';

    const NOTIFICATION_SENT = 'notification.sent';

    const WA_BACKEND_HEAD             = 'backend_head';
    const WA_BACKEND_SETTINGS         = 'backend_settings';
    const WA_BACKEND_SIDEBAR          = 'backend_sidebar';
    const WA_BACKEND_POCKET           = 'backend_pocket';
    const WA_BACKEND_ITEM_ADD         = 'backend_item_add';
    const WA_BACKEND_LIST_ACCESSES    = 'backend_list_accesses';
    const WA_BACKEND_TEAMMATE_SIDEBAR = 'backend_teammate_sidebar';
    const WA_ITEM_RENDER_LINKED       = 'item.render_linked';

    const CREATE_NOTIFICATION_CONTENT = 'create_notification_content';
}
