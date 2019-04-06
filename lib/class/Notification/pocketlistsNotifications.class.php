<?php

class pocketlistsNotifications
{
    /**
     * @var pocketlistsListModel[]
     */
    private static $lists = [];

    /**
     * @var pocketlistsListModel
     */
    private static $lm;

    /**
     * @param pocketlistsItem $item
     *
     * @return pocketlistsListOutputDecorator|pocketlistsNullList
     * @throws waException
     */
    protected static function getList($item)
    {
        if (!isset(self::$lists[$item->getListId()])) {
            if ($item->getListId()) {
                self::$lists[$item->getListId()] = $item->getList();
            } else {
                self::$lists[$item->getListId()] = (new pocketlistsNullList())->setName(_w('Stream'));
            }
        }

        return self::$lists[$item->getListId()];
    }

    /**
     * @param pocketlistsItem $item
     * @param string          $by_username
     *
     * @throws waException
     */
    public static function notifyAboutNewAssign(pocketlistsItem $item, $by_username = '')
    {
        if (!$by_username) {
            $by_username = wa()->getUser()->getName();
        }

        $list = self::getList($item);

        $listUrl = '';

        if ($list && pocketlistsRBAC::canAccessToList($list->getObject(), $item->getAssignedContactId())) {
            $listUrl = sprintf(
                '#/pocket/%s/list/%s/',
                $list->getPocketId(),
                $list->getId()
            );
        }

        $contact = $item->getAssignedContact();
        if (!self::canSend($contact)) {
            return;
        }

        /** @var pocketlistsItem $item */
        $item = new pocketlistsItemOutputDecorator($item);
        self::sendMail(
            [
                'contact_id' => $contact->getId(),
                'subject'    => 'string:✊ {str_replace(array("\r", "\n"), " ", $item->getName())|truncate:64}',
                'body'       => wa()->getAppPath('templates/mails/newassignitem.html'),
                'variables'  => [
                    'item'        => $item,
                    'due_date'    => $item->getDueDatetime()
                        ? waDateTime::format(
                            'humandatetime',
                            $item->getDueDatetime(),
                            $contact->getContact()->getTimezone()
                        )
                        : ($item->getDueDate() ? waDateTime::format(
                            'humandate',
                            $item->getDueDate(),
                            $contact->getContact()->getTimezone()
                        ) : false),
                    'list'        => new pocketlistsListOutputDecorator($list),
                    'listUrl'     => $listUrl,
                    'by_username' => $by_username,
                ],
            ],
            self::getBackendUrl($item->getAssignedContactId())
        );
    }

    /**
     * @param pocketlistsComment $comment
     *
     * @throws waException
     */
    public static function notifyAboutNewComment(pocketlistsComment $comment)
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
            [
                'app_id'  => pocketlistsHelper::APP_ID,
                'setting' => [
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_ITEM,
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_FAVORITE_ITEM,
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_ANY_LIST_ITEM,
                ],
            ]
        )->fetchAll('contact_id');

        $comment_user = $comment->getContact();

        /** @var pocketlistsItem $item */
        $item = new pocketlistsItemOutputDecorator($comment->getItem());
        $listUrl = '#/pocket/todo/';
        $list = null;
        if ($item->getListId()) {
            $list = self::getList($item);

            $listUrl = sprintf(
                '#/pocket/%s/list/%s/',
                $list->getPocketId(),
                $list->getId()
            );
        }

        $mailParams = [
            'body'      => wa()->getAppPath('templates/mails/newcomment.html'),
            'variables' => [
                'item'        => $item,
                'comment'     => new pocketlistsCommentOutputDecorator($comment),
                'by_username' => $comment_user->getName(),
                'list'        => $list ? new pocketlistsListOutputDecorator($list) : false,
                'listUrl'     => $listUrl,
            ],
        ];

        foreach ($users as $user_id => $user) { // foreach user
            $contact = new pocketlistsContact(new waContact($user_id));
            if (!self::canSend($contact)) {
                continue;
            }

            // not from NULL-list
            if ($item->getListId() === null) {
                continue;
            }

            // from NULL-list, assigned to another user or created by another user
            if ($item->getListId() === null && ($item->getAssignedContactId() != $user_id || $item->getContactId(
                    ) == $user_id)) {
                continue;
            }

            if ($item->getListId() && !pocketlistsRBAC::canAccessToList($list->getObject(), $user_id)) {
                continue;
            }

            $mailParams['contact_id'] = $user_id;

//            if ($comment['contact_id'] != $user_id) {
            switch ($user['setting']) {
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_ITEM:
                    if ($item->getContactId() == $user_id) {
                        $mailParams['subject'] = 'string:💬 {str_replace(array("\r", "\n"), " ", $item->getName())|truncate:64}';
                    }
                    break;

                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_FAVORITE_ITEM:
                    if ($item->isFavorite()) {
                        $mailParams['subject'] = 'string:💬 {str_replace(array("\r", "\n"), " ", $item->getName())|truncate:64}';
                    }
                    break;

                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_ANY_LIST_ITEM:
                    if ($item) {
                        $mailParams['subject'] = 'string:💬 {str_replace(array("\r", "\n"), " ", $item->getName())|truncate:64}';
                    }
                    break;
            }

            self::sendMail($mailParams, self::getBackendUrl($user_id));
//            }
        }
    }

    public static function notifyDailyRecap($vars = [], $test = false)
    {
        $time = time();
        $csm = new waContactSettingsModel();

        $check_time = "AND IF(cs2.value IS NULL, 0, cs2.value) <= ($time - 60*60*24)";
        if ($test) {
            $check_time = "";
        }

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
                {$check_time}";
        $users = $csm->query(
            $q,
            [
                'setting' => [
                    pocketlistsUserSettings::DAILY_RECAP_FOR_TODAY,
                    pocketlistsUserSettings::DAILY_RECAP_FOR_TODAY_AND_TOMORROW,
                    pocketlistsUserSettings::DAILY_RECAP_FOR_NEXT_7_DAYS,
                ],
            ]
        )->fetchAll('contact_id');
        $im = new pocketlistsItemModel();
        foreach ($users as $user_id => $user) {
            $contact = new waContact($user_id);
            if (!self::canSend($contact)) {
                continue;
            }

            if (wa()->getEnv() == 'cli') { // to load locale in cli
                wa()->setLocale($contact->getLocale());
            }

            $items = $im->getDailyRecapItems($contact->getId(), $user['setting']);
            if ($items) {
                self::sendMail(
                    [
                        'contact_id' => $user_id,
                        'subject'    => 'string:'.sprintf(_w("Daily recap for %s"), waDateTime::format('humandate')),
                        'body'       => wa()->getAppPath('templates/mails/dailyrecap.html'),
                        'variables'  => [
                                'items'    => $items,
                                'timezone' => $contact->getTimezone(),
                            ] + $vars,
                    ],
                    self::getBackendUrl($user_id)
                );
                $csm->set($user_id, 'pocketlists', 'last_recap_cron_time', $time);
            }
        }
    }




    /**
     * Send email to user
     *
     * @param $data
     */
    public static function sendMail($data, $backend_url = false)
    {
        try {
            $default_variables = [
                'email_settings_url' => '#/settings/',
            ];

            if (empty($data['variables'])) {
                $data['variables'] = [];
            }
            $data['variables'] = array_merge($default_variables, $data['variables']);

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

            $absolute_backend_url = $backend_url
                ? $backend_url
                : pl2()->getRootUrl(true).pl2()->getBackendUrl();

            $view->assign('backend_url', rtrim($absolute_backend_url, '/').'/pocketlists/');
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

            if (!$message->send()) {
                pocketlistsHelper::logError(sprintf('Email send error to %s', $to));
            }
        } catch (waException $ex) {
            pocketlistsHelper::logError(sprintf('Email send error to %s', $to), $ex);
        }
    }

    private static function getBackendUrl($user_id)
    {
        $us = new waContactSettingsModel();

        return $us->getOne($user_id, 'webasyst', 'backend_url');
    }

    /**
     * @param pocketlistsContact $contact
     *
     * @return bool
     */
    private static function canSend(pocketlistsContact $contact)
    {
        return $contact->isExists();
    }
}
