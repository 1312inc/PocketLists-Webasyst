<?php

class pocketlistsNotifications
{
    /**
     * Notify all related users about completed items (according to their settings)
     * @param $items array()
     */
    public static function notifyAboutCompleteItems($items, $list = false)
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

        $subject = 'string:{if !$complete}[`UNDONE`]{else}[`DONE`]{/if}: {$item.name|escape}';
        // todo: refactor
        foreach ($users as $user_id => $user) { // foreach user
            $filtered_items = array();
            switch ($user['setting']) {
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_CREATED:
                    foreach ($items as $item) { // filter items according to settings
                        if ($item['contact_id'] == $user_id && // created by mine
                            $item['complete_contact_id'] != $user_id // completed not by me
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
                                    'list_url' => wa()->getConfig()->getBackendUrl(true) . 'pocketlists/#/pocket/' . $list['pocket_id'] . '/list/' . $list['id'] . '/',
                                    'complete' => $item['status'],
                                    'item' => $item
                                ),
                            )
                        );
                    }
                    break;
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_FAVORITE:
                    $ufm = new pocketlistsUserFavoritesModel();
                    $user_items = $ufm->query(
                        "SELECT item_id FROM {$ufm->getTableName()} WHERE contact_id = {$user_id}"
                    )->fetchAll('item_id');
                    foreach ($items as $item) {
                        if (in_array($item['id'], $user_items) &&
                            $item['complete_contact_id'] != $user_id
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
                                    'list_url' => wa()->getConfig()->getBackendUrl(true) . 'pocketlists/#/pocket/' . $list['pocket_id'] . '/list/' . $list['id'] . '/',
                                    'complete' => $item['status'],
                                    'item' => $item
                                ),
                            )
                        );
                    }
                    break;
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_IN_FAVORITE_LIST:
                    $ufm = new pocketlistsUserFavoritesModel();
                    $user_lists = $ufm->query(
                        "SELECT i.key_list_id FROM {$ufm->getTableName()} uf JOIN pocketlists_item i ON uf.item_id = i.id AND i.key_list_id > 0 WHERE uf.contact_id = {$user_id}"
                    )->fetchAll('key_list_id');
                    $user_lists = array_keys($user_lists);
                    foreach ($items as $item) {
                        if (in_array($item['list_id'], $user_lists) &&
                            $item['complete_contact_id'] != $user_id
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
                                    'list_url' => wa()->getConfig()->getBackendUrl(true) . 'pocketlists/#/pocket/' . $list['pocket_id'] . '/list/' . $list['id'] . '/',
                                    'complete' => $item['status'],
                                    'item' => $item
                                ),
                            )
                        );
                    }
                    break;
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ANY_ITEM:
                    foreach ($items as $item) { // filter items according to settings
                        if ($item['complete_contact_id'] != $user_id) { // completed not by me
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
                                    'list_url' => $list ? wa()->getConfig()->getBackendUrl(true) . 'pocketlists/#/pocket/' . $list['pocket_id'] . '/list/' . $list['id'] . '/' : false,
                                    'complete' => $item['status'],
                                    'item' => $item
                                ),
                            )
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
                        if (in_array($item['list_id'], $user_lists)) {
                            $filtered_items[$item['id']] = $item;
                            $c = new waContact($item['contact_id']);
                            $filtered_items[$item['id']]['contact_name'] = $c->getName();
                        }
                    }
                    if ($filtered_items) {
                        self::sendMail(
                            array(
                                'contact_id' => $user_id,
                                'subject' => 'string:[`New to-do on your favorite list!`]',
                                'body' => wa()->getAppPath('templates/mails/newfavoritelistitem.html'),
                                'variables' => array(
                                    'list_name' => $list ? $list['name'] : false,
                                    'items' => $filtered_items
                                ),
                            )
                        );
                    }
                    break;
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_ANY_LIST:
                    foreach ($items as $item) { // filter items according to settings
                        if ($item['contact_id'] != $user_id) { // created not by user
                            $filtered_items[$item['id']] = $item;
                            $c = new waContact($item['contact_id']);
                            $filtered_items[$item['id']]['contact_name'] = $c->getName();
                        }
                    }
                    if ($filtered_items) {
                        self::sendMail(
                            array(
                                'contact_id' => $user_id,
                                'subject' => 'string:[`New to-do`]',
                                'body' => wa()->getAppPath('templates/mails/newitem.html'),
                                'variables' => array(
                                    'list_name' => $list ? $list['name'] : false,
                                    'items' => $filtered_items
                                ),
                            )
                        );
                    }
                    break;
            }
        }
    }

    public static function notifyAboutNewAssign($item)
    {
        self::sendMail(
            array(
                'contact_id' => $item['assigned_contact_id'],
                'subject' => 'string:[`NEW: You’ve been assigned to a to-do`]`',
                'body' => wa()->getAppPath('templates/mails/newassignitem.html'),
                'variables' => array(
                    'item_name' => $item['name'],
                    'due_date' => waDateTime::format('humandatetime', $item['due_date']),
                )
            )
        );
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
                AND cs2.value <= ($time - 60*60*24)";
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
            self::sendMail(
                array(
                    'contact_id' => $user_id,
                    'subject' => 'string:[`Today — WAHUMANDATE`]',
                    'body' => wa()->getAppPath('templates/mails/dailyrecap.html'),
                    'variables' => array(
                            'items' => $im->getDailyRecapItems($user_id, $user['setting'])
                        ) + $vars
                )
            );
            $csm->set($user_id, 'pocketlists', 'last_recap_cron_time', $time);
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
                            'by' => $create_contact_name,
                            'create_datetime' => $list['create_datetime'],
                        )
                    )
                );
            }
        }
    }

    /**
     * Send email to user
     * @param $data
     */
    public static function sendMail($data)
    {
        $contact = new waContact($data['contact_id']);
        $to = $contact->get('email', 'default'); // todo: add email option in settings
        if (!$to) {
            return;
        }
        $view = wa()->getView();
        $view->clearAllAssign();
        $view->clearAllCache();

        $view->assign('name', $contact->getName());
        $view->assign('now', waDateTime::date("Y-m-d H:i:s", time(), $contact->getTimezone()));
        $view->assign('backend_url', wa()->getConfig()->getBackendUrl(true) . 'pocketlists/');
        foreach ($data['variables'] as $var_name => $var_value) {
            $view->assign($var_name, $var_value);
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
}
