<?php

/**
 * Class pocketlistsNotificationDailyRecap
 */
class pocketlistsNotificationDailyRecap extends pocketlistsBaseNotification
{
    const IDENTIFIER = 'recap';

    /**
     * @param array $vars
     * @param bool  $test
     *
     * @throws waDbException
     * @throws waException
     */
    public function notify($vars = [], $test = false)
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

        /** @var pocketlistsItemFactory $itemModel */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
        /** @var pocketlistsContactFactory $contactFactory */
        $contactFactory = pl2()->getEntityFactory(pocketlistsContact::class);
        /** @var pocketlistsNotificationFactory $notificationFactory */
        $notificationFactory = pl2()->getEntityFactory(pocketlistsNotification::class);

        $sender = new pocketlistsNotificationSendService();

        foreach ($users as $user_id => $user) {
            try {
                $contact = $contactFactory->createNewWithId($user_id);
                if (!$this->canSend($contact)) {
                    continue;
                }

                if (wa()->getEnv() == 'cli') { // to load locale in cli
                    wa()->setLocale($contact->getContact()->getLocale());
                }

                $items = $itemFactory->findTodoRecap($contact, $user['setting']);

                $items = (new pocketlistsStrategyItemFilterAndSort($items))->filterDoneUndone()->getProperSortUndone();

                if ($items) {
                    $itemsToSend = [];
                    $iconFinder = new pocketlistsItemIcon();
                    foreach ($items as $item) {
                        $itemsToSend[$item->getId()] = [
                            'icon' => $iconFinder->getIconByItemPriority($item->getPriority()),
                            'due_datetime' => $item->getDueDatetime(),
                            'due_date' => $item->getDueDate(),
                            'name_parsed' => $item->getNameParsed(),
                            'note' => $item->getNote(),
                            'note_parsed' => $item->getNoteParsed(),
                        ];
                    }

                    $emailContent = new pocketlistsNotificationEmailContent();
                    $emailContent
                        ->setToContactId($contact->getId())
                        ->setToEmail($contact->getEmail())
                        ->setParams(
                            [
                                'items' => $itemsToSend,
                                'timezone' => new DateTimeZone($contact->getContact()->getTimezone()),
                            ] + $vars
                        )
                        ->setSubject('string:📥 '.sprintf(_w("Daily recap for %s"), waDateTime::format('humandate')))
                        ->setTemplate('templates/mails/dailyrecap.html');

                    $notification = $notificationFactory
                        ->createNewEmail($emailContent)
                        ->setIdentifier(self::IDENTIFIER);
                    $notificationFactory->insert($notification);

                    $sender->send($notification);

                    $csm->set($user_id, 'pocketlists', 'last_recap_cron_time', $time);
                }
            } catch (Exception $ex) {
                pocketlistsLogger::error(
                    sprintf(
                        'Error on sending daily recap for user %s. Error: %s. Trace: %s',
                        $user_id,
                        $ex->getMessage(),
                        $ex->getTraceAsString()
                    ),
                    'notifications.log'
                );

                if (isset($notification)) {
                    $notification
                        ->setStatus(pocketlistsNotification::STATUS_FAIL)
                        ->setError($ex->getMessage());
                    $notificationFactory->save($notification);
                }
            }
        }
    }

    /**
     * @param pocketlistsContact $contact
     *
     * @return bool
     */
    protected function canSend(pocketlistsContact $contact)
    {
        return parent::canSend($contact) || $contact->isMe();
    }
}
