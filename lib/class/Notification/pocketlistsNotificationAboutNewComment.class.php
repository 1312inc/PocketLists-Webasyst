<?php

/**
 * Class pocketlistsNotificationAboutNewComment
 */
class pocketlistsNotificationAboutNewComment extends pocketlistsBaseNotification
{
    /**
     * @param pocketlistsComment $comment
     *
     * @throws waException
     */
    public function notify(pocketlistsComment $comment)
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
        $item = $comment->getItem();
        $listUrl = '#/pocket/todo/';
        $list = null;
        if ($item->getListId()) {
            $list = $this->getList($item);

            $listUrl = sprintf(
                '#/pocket/%s/list/%s/',
                $list->getPocketId(),
                $list->getId()
            );
        }

        $mailData = [
            'subject' => '',
            'body'   => wa()->getAppPath('templates/mails/newcomment.html'),
            'params' => [
                'item'        => [
                    'name' => $item->getName(),
                ],
                'comment'     => [
                    'name_parsed' => $comment->getCommentParsed(),
                ],
                'by_username' => $comment_user->getName(),
                'list'        => $list ? [
                    'url' => $listUrl,
                ] : false,
            ],
        ];

        /** @var pocketlistsNotificationFactory $notificationFactory */
        $notificationFactory = pl2()->getEntityFactory(pocketlistsNotification::class);

        $contactFactory = pl2()->getEntityFactory(pocketlistsContact::class);

        foreach ($users as $user_id => $user) { // foreach user
            $contact = $contactFactory->createNewWithId($user_id);
            if (!$this->canSend($contact)) {
                continue;
            }

            // not from NULL-list
            if ($item->getListId() === null) {
                continue;
            }

            // from NULL-list, assigned to another user or created by another user
            if ($item->getListId() === null
                && ($item->getAssignedContactId() != $user_id || $item->getContactId() == $user_id)) {
                continue;
            }

            if ($item->getListId() && !pocketlistsRBAC::canAccessToList($list, $user_id)) {
                continue;
            }

            $mailData['contact_id'] = $user_id;

            switch ($user['setting']) {
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_ITEM:
                    if ($item->getContactId() == $user_id) {
                        $mailData['subject'] = 'string:💬 {str_replace(array("\r", "\n"), " ", $item.name)|truncate:64}';
                    }
                    break;

                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_MY_FAVORITE_ITEM:
                    if ($item->isFavorite()) {
                        $mailData['subject'] = 'string:💬 {str_replace(array("\r", "\n"), " ", $item.name)|truncate:64}';
                    }
                    break;

                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_COMMENT_TO_ANY_LIST_ITEM:
                    if ($item) {
                        $mailData['subject'] = 'string:💬 {str_replace(array("\r", "\n"), " ", $item.name)|truncate:64}';
                    }
                    break;
            }

            $emailContent = new pocketlistsNotificationEmailContent();
            $emailContent
                ->setToContactId($mailData['contact_id'])
                ->setParams($mailData['params'])
                ->setSubject($mailData['subject'])
                ->setTemplate($mailData['body']);

            $notificationFactory->insert($notificationFactory->createNewEmail($emailContent));
        }
    }

}