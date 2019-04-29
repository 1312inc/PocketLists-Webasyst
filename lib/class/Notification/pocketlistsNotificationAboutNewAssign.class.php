<?php

/**
 * Class pocketlistsNotificationAboutNewAssign
 */
class pocketlistsNotificationAboutNewAssign extends pocketlistsBaseNotification
{
    /**
     * Notify all related users about new items (according to their settings)
     *
     * @param pocketlistsItem         $item
     * @param pocketlistsContact|null $by_username
     *
     * @throws waException
     */
    public function notify(pocketlistsItem $item, pocketlistsContact $by_username = null)
    {
        if (!$by_username) {
            $by_username = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId(
                wa()->getUser()->getId()
            );
        }

        $list = $this->getList($item);

        $listUrl = '';

        if ($list && pocketlistsRBAC::canAccessToList($list, $item->getAssignedContactId())) {
            $listUrl = sprintf(
                '#/pocket/%s/list/%s/',
                $list->getPocketId(),
                $list->getId()
            );
        }

        $contact = $item->getAssignedContact();
        if (!$contact) {
            return;
        }

        if (!$this->canSend($contact)) {
            return;
        }

        if ($contact->isMe()) {
            return;
        }

        /** @var pocketlistsNotificationFactory $notificationFactory */
        $notificationFactory = pl2()->getEntityFactory(pocketlistsNotification::class);

        $emailContent = new pocketlistsNotificationEmailContent();
        $emailContent
            ->setToContactId($contact->getId())
            ->setToEmail($contact->getEmail())
            ->setParams(
                [
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
                    'by_username' => $by_username->getName(),
                    'list'        => $list ? [
                        'name_parsed' => $list->getNameParsed(),
                        'url'         => $listUrl,
                    ] : false,
                    'item'        => [
                        'name'        => $item->getName(),
                        'name_parsed' => $item->getNameParsed(),
                    ],
                ]
            )
            ->setSubject('string:➡️ {str_replace(array("\r", "\n"), " ", $item.name)|truncate:64}')
            ->setTemplate(wa()->getAppPath('templates/mails/newassignitem.html'));

        $notificationFactory->insert($notificationFactory->createNewEmail($emailContent));
    }
}
