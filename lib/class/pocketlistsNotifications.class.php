<?php

class pocketlistsNotifications
{
    /**
     * Notify all related users (according to their settings about items
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
                    self::sendMail(
                        array(
                            'contact_id' => $user_id,
                            'subject' => 'string:Item you created was completed',
                            'body' => wa()->getAppPath('templates/mails/completeitem.html'),
                            'variables' => array(
                                'type' => pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_CREATED,
                                'items' => $filtered_items
                            ),
                        )
                    );
                    break;
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_FAVORITE:
                    // todo: get favs items for user;
                    break;
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_IN_FAVORITE_LIST:
                    // todo: get favs lists for user;
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
                        self::sendMail(
                            array(
                                'contact_id' => $user_id,
                                'subject' => 'string:Item was completed',
                                'body' => wa()->getAppPath('templates/mails/completeitem.html'),
                                'variables' => array(
                                    'type' => pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ANY_ITEM,
                                    'items' => $filtered_items
                                ),
                            )
                        );
                    }
                    break;
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

        $view->assign('name', $contact->getName());
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
