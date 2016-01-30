<?php

class pocketlistsNotifications
{
    /**
     * Notify all related users about completed items (according to their settings)
     * @param $items array()
     */
    public static function notifyAboutCompleteItems($items)
    {
        if (!count($items)) {
            return;
        }
        if (!is_array($items)) {
            $items = array($items);
        }
        $csm = new waContactSettingsModel();
        $q = "SELECT
                cs1.contact_id contact_id,
                cs2.value setting
              FROM wa_contact_settings cs1
              LEFT JOIN wa_contact_settings cs2 ON
                cs1.contact_id = cs2.contact_id
                AND cs2.app_id = s:app_id
                AND cs2.name = 'email_complete_item'
                AND cs2.value IN (i:setting)
              WHERE
                cs1.app_id = s:app_id
                AND cs1.name = 'email_complete_item_on'
                AND cs1.value = 1";
        $users = $csm->query(
            $q,
            array(
                'app_id' => wa()->getApp(),
                'setting' => array(
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_CREATED,
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_FAVORITE,
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_IN_FAVORITE_LIST,
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ANY_ITEM,
                )
            )
        )->fetchAll('contact_id');

        $lm = new pocketlistsListModel();
        $im = new pocketlistsItemModel();

        $subject = 'string:{if !$complete}✖{else}✔{/if} {str_replace(array("\r", "\n"), " ", $item.name)|truncate:64}';
        // todo: refactor
        foreach ($users as $user_id => $user) { // foreach user
            $filtered_items = array();
            switch ($user['setting']) {
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_CREATED:
                    foreach ($items as $item) { // filter items according to settings
                        if ($item['contact_id'] == $user_id && // created by mine
                            $item['complete_contact_id'] != $user_id && // completed not by me
                            (
                                $item['list_id'] || ( // not from NULL-list
                                    $item['list_id'] == null && ( // OR from NULL-list,
                                        isset($item['assigned_contact_id']) && $item['assigned_contact_id'] == $user_id || // but assigned to this user
                                        $item['contact_id'] == $user_id // OR created by user
                                    )
                                )
                            )
                        ) {
                            $filtered_items[$item['id']] = $item;
                            $c = new waContact($item['complete_contact_id']);
                            $filtered_items[$item['id']]['complete_contact_name'] = $c->getName();
                        }
                    }
                    if ($filtered_items) {
                        $item = reset($filtered_items);
                        $list = $lm->getById($item['list_id']);
                        $items_left = count($im->getUndoneByList($list['id'], false));
                        self::sendMail(
                            array(
                                'contact_id' => $user_id,
                                'subject' => $subject,
                                'body' => wa()->getAppPath('templates/mails/completeanyitem.html'),
                                'variables' => array(
                                    'n' => $items_left,
                                    'list' => $list,
                                    'list_url' => '#/pocket/' . $list['pocket_id'] . '/list/' . $list['id'] . '/',
                                    'complete' => $item['status'],
                                    'item' => $item
                                ),
                            ),
                            self::getBackendUrl($user_id)
                        );
                    }
                    break;
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_FAVORITE:
                    $ufm = new pocketlistsUserFavoritesModel();
                    $user_items = $ufm->query(
                        "SELECT item_id FROM {$ufm->getTableName()} WHERE contact_id = {$user_id}"
                    )->fetchAll('item_id');
                    foreach ($items as $item) {
                        if (in_array($item['id'], array_keys($user_items)) &&
                            $item['complete_contact_id'] != $user_id && (
                                $item['list_id'] || ( // not from NULL-list
                                    $item['list_id'] == null && ( // OR from NULL-list,
                                        isset($item['assigned_contact_id']) && $item['assigned_contact_id'] == $user_id || // but assigned to this user
                                        $item['contact_id'] == $user_id // OR created by user
                                    )
                                )
                            )
                        ) {
                            $filtered_items[$item['id']] = $item;
                            $c = new waContact($item['complete_contact_id']);
                            $filtered_items[$item['id']]['complete_contact_name'] = $c->getName();
                        }
                    }
                    if ($filtered_items) {
                        $item = reset($filtered_items);
                        $list = $lm->getById($item['list_id']);
                        $items_left = count($im->getUndoneByList($list['id'], false));
                        self::sendMail(
                            array(
                                'contact_id' => $user_id,
                                'subject' => $subject,
                                'body' => wa()->getAppPath('templates/mails/completeanyitem.html'),
                                'variables' => array(
                                    'n' => $items_left,
                                    'list' => $list,
                                    'list_url' => '#/pocket/' . $list['pocket_id'] . '/list/' . $list['id'] . '/',
                                    'complete' => $item['status'],
                                    'item' => $item
                                ),
                            ),
                            self::getBackendUrl($user_id)
                        );
                    }
                    break;
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_IN_FAVORITE_LIST:
                    $ufm = new pocketlistsUserFavoritesModel();
                    $user_lists = $ufm->query(
                        "SELECT i.key_list_id FROM {$ufm->getTableName()} uf JOIN pocketlists_item i ON uf.item_id = i.id AND i.key_list_id > 0 WHERE uf.contact_id = {$user_id}"
                    )->fetchAll('key_list_id');
                    foreach ($items as $item) {
                        if (in_array($item['list_id'], array_keys($user_lists)) &&
                            $item['complete_contact_id'] != $user_id && (
                                $item['list_id'] || ( // not from NULL-list
                                    $item['list_id'] == null && ( // OR from NULL-list,
                                        isset($item['assigned_contact_id']) && $item['assigned_contact_id'] == $user_id || // but assigned to this user
                                        $item['contact_id'] == $user_id // OR created by user
                                    )
                                )
                            )
                        ) {
                            $filtered_items[$item['id']] = $item;
                            $c = new waContact($item['complete_contact_id']);
                            $filtered_items[$item['id']]['complete_contact_name'] = $c->getName();
                        }
                    }
                    if ($filtered_items) {
                        $item = reset($filtered_items);
                        $list = $lm->getById($item['list_id']);
                        $items_left = count($im->getUndoneByList($list['id'], false));
                        self::sendMail(
                            array(
                                'contact_id' => $user_id,
                                'subject' => $subject,
                                'body' => wa()->getAppPath('templates/mails/completeanyitem.html'),
                                'variables' => array(
                                    'n' => $items_left,
                                    'list' => $list,
                                    'list_url' => '#/pocket/' . $list['pocket_id'] . '/list/' . $list['id'] . '/',
                                    'complete' => $item['status'],
                                    'item' => $item
                                ),
                            ),
                            self::getBackendUrl($user_id)
                        );
                    }
                    break;
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ANY_ITEM:
                    foreach ($items as $item) { // filter items according to settings
                        if ($item['complete_contact_id'] != $user_id && (
                                $item['list_id'] || ( // not from NULL-list
                                    $item['list_id'] == null && ( // OR from NULL-list,
                                        isset($item['assigned_contact_id']) && $item['assigned_contact_id'] == $user_id || // but assigned to this user
                                        $item['contact_id'] == $user_id // OR created by user
                                    )
                                )
                            )
                        ) { // completed not by me & not from NULL-list
                            $filtered_items[$item['id']] = $item;
                            $c = new waContact($item['complete_contact_id']);
                            $filtered_items[$item['id']]['complete_contact_name'] = $c->getName();
                        }
                    }
                    if ($filtered_items) {
                        $item = reset($filtered_items);
                        $list = $lm->getById($item['list_id']);
                        $items_left = count($im->getUndoneByList($list['id'], false));
                        self::sendMail(
                            array(
                                'contact_id' => $user_id,
                                'subject' => $subject,
                                'body' => wa()->getAppPath('templates/mails/completeanyitem.html'),
                                'variables' => array(
                                    'n' => $items_left,
                                    'list' => $list,
                                    'list_url' => $list ? '#/pocket/' . $list['pocket_id'] . '/list/' . $list['id'] . '/' : false,
                                    'complete' => $item['status'],
                                    'item' => $item
                                ),
                            ),
                            self::getBackendUrl($user_id)
                        );
                    }
                    break;
            }
        }
    }

    /**
     * Notify all related users about new items (according to their settings)
     * @param $items array()
     */
    public static function notifyAboutNewItems($items, $list = false)
    {
        if (!count($items)) {
            return;
        }
        if (!is_array($items)) {
            $items = array($items);
        }
        $csm = new waContactSettingsModel();
        $q = "SELECT
                cs1.contact_id contact_id,
                cs2.value setting
              FROM wa_contact_settings cs1
              LEFT JOIN wa_contact_settings cs2 ON
                cs1.contact_id = cs2.contact_id
                AND cs2.app_id = s:app_id
                AND cs2.name = 'email_add_item'
                AND cs2.value IN (i:setting)
              WHERE
                cs1.app_id = s:app_id
                AND cs1.name = 'email_add_item_on'
                AND cs1.value = 1";
        $users = $csm->query(
            $q,
            array(
                'app_id' => wa()->getApp(),
                'setting' => array(
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_FAVORITE_LIST,
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_ANY_LIST,
                )
            )
        )->fetchAll('contact_id');

        foreach ($users as $user_id => $user) { // foreach user
            $filtered_items = array();
            switch ($user['setting']) {
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_FAVORITE_LIST:
                    $ufm = new pocketlistsUserFavoritesModel();
                    $user_lists = $ufm->query(
                        "SELECT i.key_list_id FROM {$ufm->getTableName()} uf JOIN pocketlists_item i ON uf.item_id = i.id AND i.key_list_id > 0 WHERE uf.contact_id = {$user_id}"
                    )->fetchAll('key_list_id');
                    $user_lists = array_keys($user_lists);
                    foreach ($items as $item) {
                        if (in_array($item['list_id'], $user_lists) && (
                                $item['list_id'] || ( // not from NULL-list
                                    $item['list_id'] == null && ( // OR from NULL-list,
                                        isset($item['assigned_contact_id']) && $item['assigned_contact_id'] == $user_id || // but assigned to this user
                                        $item['contact_id'] == $user_id // OR created by user
                                    )
                                )
                            )
                        ) {
                            $filtered_items[$item['id']] = $item;
                            $c = new waContact($item['contact_id']);
                            $filtered_items[$item['id']]['contact_name'] = $c->getName();
                        }
                    }
                    if ($filtered_items) {
                        self::sendMail(
                            array(
                                'contact_id' => $user_id,
                                'subject' => 'string:❍ {str_replace(array("\r", "\n"), " ", $item.name)|truncate:64}',
                                'body' => wa()->getAppPath('templates/mails/newfavoritelistitem.html'),
                                'variables' => array(
                                    'list_name' => $list ? $list['name'] : false,
                                    'list_url' => '#/pocket/' . $list['pocket_id'] . '/list/' . $list['id'] . '/',
                                    'items' => $filtered_items,
                                    'item' => reset($filtered_items)
                                ),
                            ),
                            self::getBackendUrl($user_id)
                        );
                    }
                    break;
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_ANY_LIST:
                    foreach ($items as $item) { // filter items according to settings
                        if ($item['contact_id'] != $user_id && // created not by this user
                            (
                                $item['list_id'] || ( // not from NULL-list
                                    $item['list_id'] == null && ( // OR from NULL-list,
                                        isset($item['assigned_contact_id']) && $item['assigned_contact_id'] == $user_id || // but assigned to this user
                                        $item['contact_id'] == $user_id // OR created by user
                                    )
                                )
                            )
                        ) {
                            $filtered_items[$item['id']] = $item;
                            $c = new waContact($item['contact_id']);
                            $filtered_items[$item['id']]['contact_name'] = $c->getName();
                        }
                    }
                    if ($filtered_items) {
                        self::sendMail(
                            array(
                                'contact_id' => $user_id,
                                'subject' => 'string:❍ {str_replace(array("\r", "\n"), " ", $item.name)|truncate:64}',
                                'body' => wa()->getAppPath('templates/mails/newitem.html'),
                                'variables' => array(
                                    'list_name' => $list ? $list['name'] : false,
                                    'list_url' => '#/pocket/' . $list['pocket_id'] . '/list/' . $list['id'] . '/',
                                    'items' => $filtered_items,
                                    'item' => reset($filtered_items)
                                ),
                            ),
                            self::getBackendUrl($user_id)
                        );
                    }
                    break;
            }
        }
    }

    public static function notifyAboutNewAssign($item, $by_username)
    {
        $lm = new pocketlistsListModel();
        $list = array(
            'url' => wa()->getConfig()->getRootUrl(true) . '/' . wa()->getConfig()->getBackendUrl() . 'pocketlists/#/todo/',
            'name' => _('Stream')
        );
        if ($item['list_id']) {
            $list_ = $lm->getById($item['list_id']);
            $list = array(
                'url' => wa()->getConfig()->getRootUrl(true) . '/' . wa()->getConfig()->getBackendUrl() . 'pocketlists/#/pocket/' . $list_['pocket_id'] . '/list/' . $list_['id'] . '/',
                'name' => $list_['name']
            );
        }
        self::sendMail(
            array(
                'contact_id' => $item['assigned_contact_id'],
                'subject' => 'string:➔ {str_replace(array("\r", "\n"), " ", $item_name)|truncate:64}',
                'body' => wa()->getAppPath('templates/mails/newassignitem.html'),
                'variables' => array(
                    'item_name' => $item['name'],
                    'due_date' => waDateTime::format('humandatetime', $item['due_date']),
                    'list' => $list,
                    'by_username' => $by_username
                )
            ),
            self::getBackendUrl($item['assigned_contact_id'])
        );
    }

    public static function notifyAboutNewComment($comment)
    {
        if (!$comment) {
            return;
        }

        // todo: refactor
        $csm = new waContactSettingsModel();
        $q = "SELECT
                cs1.contact_id contact_id,
                cs2.value setting
              FROM wa_contact_settings cs1
              LEFT JOIN wa_contact_settings cs2 ON
                cs1.contact_id = cs2.contact_id
                AND cs2.app_id = s:app_id
                AND cs2.name = 'email_comment_item'
                AND cs2.value IN (i:setting)
              WHERE
                cs1.app_id = s:app_id
                AND cs1.name = 'email_comment_item_on'
                AND cs1.value = 1";
        $users = $csm->query(
            $q,
            array(
                'app_id' => wa()->getApp(),
                'setting' => array(
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_ITEM,
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_FAVORITE_ITEM,
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_ANY_LIST_ITEM,
                )
            )
        )->fetchAll('contact_id');

        $comment_user = new waUser($comment['contact_id']);
        $lm = new pocketlistsListModel();
        $list = array(
            'url' => wa()->getConfig()->getRootUrl(true) . '/' . wa()->getConfig()->getBackendUrl() . 'pocketlists/#/todo/',
            'name' => _('Stream')
        );
        foreach ($users as $user_id => $user) { // foreach user
            if ($comment['contact_id'] != $user_id) {
                switch ($user['setting']) {
                    case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_ITEM:
                        $im = new pocketlistsItemModel();
                        $item = $im->getById($comment['item_id']);
                        if ($item['list_id']) {
                            $list_ = $lm->getById($item['list_id']);
                            $list = array(
                                'url' => wa()->getConfig()->getRootUrl(true) . '/' . wa()->getConfig()->getBackendUrl() . 'pocketlists/#/pocket/' . $list_['pocket_id'] . '/list/' . $list_['id'] . '/',
                                'name' => $list_['name']
                            );
                        }
                        if ($item['contact_id'] == $user_id && (
                                $item['list_id'] || ( // not from NULL-list
                                    $item['list_id'] == null && ( // OR from NULL-list,
                                        isset($item['assigned_contact_id']) && $item['assigned_contact_id'] == $user_id || // but assigned to this user
                                        $item['contact_id'] == $user_id // OR created by user
                                    )
                                )
                            )
                        ) {
                            self::sendMail(
                                array(
                                    'contact_id' => $user_id,
                                    'subject' => 'string:✍ {sprintf("[`New comment on %s`]", str_replace(array("\r", "\n"), " ", $item.name)|escape|truncate:32)}',
                                    'body' => wa()->getAppPath('templates/mails/newcomment.html'),
                                    'variables' => array(
                                        'item' => $item,
                                        'comment' => $comment,
                                        'by_username' => $comment_user->getName(),
                                        'list' => $list
                                    ),
                                ),
                                self::getBackendUrl($user_id)
                            );
                        }
                        break;
                    case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_FAVORITE_ITEM:
                        $im = new pocketlistsItemModel();
                        $item = $im->getById($comment['item_id'], $user_id);
                        if ($item['list_id']) {
                            $list_ = $lm->getById($item['list_id']);
                            $list = array(
                                'url' => '#/pocket/' . $list_['pocket_id'] . '/list/' . $list_['id'] . '/',
                                'name' => $list_['name']
                            );
                        }
                        if ($item['favorite'] && (
                                $item['list_id'] || ( // not from NULL-list
                                    $item['list_id'] == null && ( // OR from NULL-list,
                                        isset($item['assigned_contact_id']) && $item['assigned_contact_id'] == $user_id || // but assigned to this user
                                        $item['contact_id'] == $user_id // OR created by user
                                    )
                                )
                            )
                        ) {
                            self::sendMail(
                                array(
                                    'contact_id' => $user_id,
                                    'subject' => 'string:✍ {sprintf("[`New comment on %s`]", str_replace(array("\r", "\n"), " ", $item.name)|escape|truncate:32)}',
                                    'body' => wa()->getAppPath('templates/mails/newcomment.html'),
                                    'variables' => array(
                                        'item' => $item,
                                        'comment' => $comment,
                                        'by_username' => $comment_user->getName(),
                                        'list' => $list
                                    ),
                                ),
                                self::getBackendUrl($user_id)
                            );
                        }
                        break;
                    case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_ANY_LIST_ITEM:
                        $im = new pocketlistsItemModel();
                        $item = $im->getById($comment['item_id']);
                        if ($item['list_id']) {
                            $list_ = $lm->getById($item['list_id']);
                            $list = array(
                                'url' => '#/pocket/' . $list_['pocket_id'] . '/list/' . $list_['id'] . '/',
                                'name' => $list_['name']
                            );
                        }
                        if ($item && (
                                $item['list_id'] || ( // not from NULL-list
                                    $item['list_id'] == null && ( // OR from NULL-list,
                                        isset($item['assigned_contact_id']) && $item['assigned_contact_id'] == $user_id || // but assigned to this user
                                        $item['contact_id'] == $user_id // OR created by user
                                    )
                                )
                            )
                        ) {
                            self::sendMail(
                                array(
                                    'contact_id' => $user_id,
                                    'subject' => 'string:✍ {sprintf("[`New comment on %s`]", str_replace(array("\r", "\n"), " ", $item.name)|escape|truncate:32)}',
                                    'body' => wa()->getAppPath('templates/mails/newcomment.html'),
                                    'variables' => array(
                                        'item' => $item,
                                        'comment' => $comment,
                                        'by_username' => $comment_user->getName(),
                                        'list' => $list
                                    ),
                                ),
                                self::getBackendUrl($user_id)
                            );
                        }
                        break;
                }
            }
        }
    }

    public static function notifyDailyRecap($vars = array())
    {
        $time = time();
        $csm = new waContactSettingsModel();
        // get recap setting for all users and do not select users who received daily recap less then 24 hours ago
        $q = "SELECT
                cs1.contact_id contact_id,
                cs2.value last_recap_cron_time,
                cs3.value setting
              FROM wa_contact_settings cs1
              LEFT JOIN wa_contact_settings cs2 ON
                cs2.contact_id = cs1.contact_id
                AND cs2.app_id = 'pocketlists'
                AND cs2.name = 'last_recap_cron_time'
              LEFT JOIN wa_contact_settings cs3 ON
                cs3.contact_id = cs1.contact_id
                AND cs3.app_id = 'pocketlists'
                AND cs3.name = 'daily_recap'
                AND cs3.value IN (i:setting)
              WHERE
                cs1.app_id = 'pocketlists'
                AND cs1.name = 'daily_recap_on'
                AND cs1.value = 1
                AND IF(cs2.value IS NULL, 0, cs2.value) <= ($time - 60*60*24)";
        $users = $csm->query(
            $q,
            array(
                'setting' => array(
                    pocketlistsUserSettings::DAILY_RECAP_FOR_TODAY,
                    pocketlistsUserSettings::DAILY_RECAP_FOR_TODAY_AND_TOMORROW,
                    pocketlistsUserSettings::DAILY_RECAP_FOR_NEXT_7_DAYS
                )
            )
        )->fetchAll('contact_id');
        $im = new pocketlistsItemModel();
        foreach ($users as $user_id => $user) {
            $items = $im->getDailyRecapItems($user_id, $user['setting']);
            if ($items) {
                self::sendMail(
                    array(
                        'contact_id' => $user_id,
                        'subject' => 'string:' . sprintf(_w("Daily recap for %s"), waDateTime::format('humandate')),
                        'body' => wa()->getAppPath('templates/mails/dailyrecap.html'),
                        'variables' => array(
                                'items' => $items
                        ) + $vars
                    ),
                    self::getBackendUrl($user_id)
                );
                $csm->set($user_id, 'pocketlists', 'last_recap_cron_time', $time);
            }
        }
    }

    public static function notifyAboutNewList($list)
    {
        $csm = new waContactSettingsModel();
        $q = "SELECT
                cs1.contact_id contact_id
              FROM wa_contact_settings cs1
              WHERE
                cs1.app_id = s:app_id
                AND cs1.name = 'email_create_list_on'
                AND cs1.value = 1";
        $users = $csm->query(
            $q,
            array(
                'app_id' => wa()->getApp(),
            )
        )->fetchAll('contact_id');

        $c = new waContact($list['contact_id']);
        $create_contact_name = $c->getName();
        $list['create_datetime'] = waDateTime::format('humandatetime', $list['create_datetime']);
        foreach ($users as $user_id => $user) { // foreach user
            if ($list['contact_id'] != $user_id) { // created not by user
                self::sendMail(
                    array(
                        'contact_id' => $user_id,
                        'subject' => 'string:[`New list!`]`',
                        'body' => wa()->getAppPath('templates/mails/newlist.html'),
                        'variables' => array(
                            'list_name' => $list['name'],
                            'list_url' => '#/pocket/' . $list['pocket_id'] . '/list/' . $list['id'] . '/',
                            'by' => $create_contact_name,
                            'create_datetime' => $list['create_datetime'],
                        )
                    ),
                    self::getBackendUrl($user_id)
                );
            }
        }
    }

    /**
     * Send email to user
     * @param $data
     */
    public static function sendMail($data, $backend_url = false)
    {
        $to = false;
        $view = wa()->getView();
        $view->clearAllAssign();
        $view->clearAllCache();

        if (isset($data['contact_id'])) {
            $contact = new waContact($data['contact_id']);
            $to = $contact->get('email', 'default'); // todo: add email option in settings
            $view->assign('name', $contact->getName());
            $view->assign('now', waDateTime::date("Y-m-d H:i:s", time(), $contact->getTimezone()));
        } elseif (isset($data['to'])) {
            $to = $data['to'];
        }

        if (!$to) {
            return;
        }

        $absolute_backend_url = $backend_url ? $backend_url : wa()->getConfig()->getRootUrl(true) . wa()->getConfig()->getBackendUrl();
        $view->assign('backend_url', $absolute_backend_url . 'pocketlists/');
        if (isset($data['variables'])) {
            foreach ($data['variables'] as $var_name => $var_value) {
                $view->assign($var_name, $var_value);
            }
        }

        $subject = $view->fetch($data['subject']);
        $body = $view->fetch($data['body']);

        $message = new waMailMessage($subject, $body);
        $message->setTo($to);
// todo: settings?
//        $message->setFrom('pocketlists@webasyst.ru', 'Pocketlists Notifier');

        if ($message->send()) {
        }
    }

    private static function getBackendUrl($user_id)
    {
        $us = new waContactSettingsModel();
        return $us->getOne($user_id, 'webasyst', 'backend_url') . '/';
    }
}
