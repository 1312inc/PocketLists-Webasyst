<?php

/**
 * Class pocketlistsNotificationAboutNewAssign
 */
class pocketlistsNotificationAboutNewAssign extends pocketlistsBaseNotification
{
    const IDENTIFIER = 'new_assign';

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
                'lists/%s/',
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

        $iconFinder = new pocketlistsItemIcon();
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
                            new DateTimeZone($contact->getContact()->getTimezone())
                        )
                        : ($item->getDueDate() ? waDateTime::format(
                            'humandate',
                            $item->getDueDate(),
                            new DateTimeZone($contact->getContact()->getTimezone())
                        ) : false),
                    'by_username' => $by_username->getName(),
                    'list'        => $list ? [
                        'name_parsed' => $list->getNameParsed(),
                        'url'         => $listUrl,
                        'id'          => (int)$list->getId(),
                    ] : false,
                    'item'        => [
                        'icon'        => $iconFinder->getIconByItemPriority($item->getPriority()),
                        'name'        => $item->getName(),
                        'name_parsed' => $item->getNameParsed(),
                        'note' => $item->getNote(),
                        'note_parsed' => $item->getNoteParsed(),
                    ],
                ]
            )
            ->setSubject('string:➡️ {str_replace(array("\r", "\n"), " ", $item.name)|truncate:64}')
            ->setTemplate('templates/mails/newassignitem.html');

        $notificationFactory->insert(
            $notificationFactory
                ->createNewEmail($emailContent)
                ->setIdentifier(self::IDENTIFIER)
        );
    }

    public function multiplicityNotify($items = [])
    {
        $user_id = pl2()->getUser()->getId();
        /** @var pocketlistsItemFactory $item_factory */
        $item_factory = pl2()->getEntityFactory(pocketlistsItem::class);

        /** @var pocketlistsItem $item */
        $item = $item_factory->createNew();
        foreach ((array) $items as $_item) {
            if (empty($_item['assigned_contact_id']) || $user_id == $_item['assigned_contact_id']) {
                continue;
            }
            $item_clone = clone $item;
            pl2()->getHydrator()->hydrate($item_clone, $_item);
            $this->notify($item_clone);
        }
    }
}
