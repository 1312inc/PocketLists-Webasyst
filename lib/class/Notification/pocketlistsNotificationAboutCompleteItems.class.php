<?php

/**
 * Class pocketlistsNotificationsAboutNewItems
 */
class pocketlistsNotificationAboutCompleteItems extends pocketlistsBaseNotification
{
    const IDENTIFIER = 'complete_items';

    /**
     * Notify all related users about completed items (according to their settings)
     *
     * @param pocketlistsItem[] $items
     *
     * @throws waException
     */
    public function notifyAboutCompleteItems($items)
    {
        if (!$items) {
            return;
        }

        if (!is_array($items)) {
            $items = [$items];
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
            [
                'app_id'  => wa()->getApp(),
                'setting' => [
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPLETES_ITEM_I_CREATED,
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_FAVORITE,
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_IN_FAVORITE_LIST,
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ANY_ITEM,
                ],
            ]
        )->fetchAll('contact_id');

        /** @var pocketlistsUserFavoritesModel $ufm */
        $ufm = pl2()->getModel('pocketlistsUserFavorites');
        $contactFactory = pl2()->getEntityFactory(pocketlistsContact::class);

        /** @var pocketlistsNotificationFactory $notificationFactory */
        $notificationFactory = pl2()->getEntityFactory(pocketlistsNotification::class);

        $subject = 'string:{if $complete}☑️{else}🔙{/if} {str_replace(array("\r", "\n"), " ", $item.name)|truncate:64}';
        // todo: refactor
        foreach ($users as $user_id => $user) { // foreach user
            $contact = $contactFactory->createNewWithId($user_id);
            if (!$this->canSend($contact)) {
                continue;
            }

            $filtered_items = [];
            switch ($user['setting']) {
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPLETES_ITEM_I_CREATED:
                    foreach ($items as $item) { // filter items according to settings
                        // created NOT by user
                        if ($item->getContactId() != $user_id && $item->getAssignedContactId() != $user_id) {
                            continue;
                        }

                        if (!$this->checkCompleteItem($item, $user_id)) {
                            continue;
                        }

                        $filtered_items[$item->getId()] = $item;
                    }

                    break;

                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_I_FAVORITE:
                    $user_items = $ufm->query(
                        "SELECT item_id FROM {$ufm->getTableName()} WHERE contact_id = {$user_id}"
                    )->fetchAll('item_id');

                    foreach ($items as $item) {
                        if (!array_key_exists($item->getId(), $user_items)) {
                            continue;
                        }

                        if (!$this->checkCompleteItem($item, $user_id)) {
                            continue;
                        }

                        $filtered_items[$item->getId()] = $item;
                    }

                    break;

                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ITEM_IN_FAVORITE_LIST:
                    $user_lists = $ufm->query(
                        "SELECT i.key_list_id FROM {$ufm->getTableName()} uf JOIN pocketlists_item i ON uf.item_id = i.id AND i.key_list_id > 0 WHERE uf.contact_id = {$user_id}"
                    )->fetchAll('key_list_id');

                    foreach ($items as $item) {
                        if (!array_key_exists($item->getListId(), $user_lists)) {
                            continue;
                        }

                        if (!$this->checkCompleteItem($item, $user_id)) {
                            continue;
                        }

                        $filtered_items[$item->getId()] = $item;
                    }

                    break;
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_COMPETES_ANY_ITEM:
                    foreach ($items as $item) { // filter items according to settings
                        if (!$this->checkCompleteItem($item, $user_id)) {
                            continue;
                        }

                        // not from NULL-list
                        if (!$item->getListId()) {
                            continue;
                        }

                        // from NULL-list but not assigned to this user nor created by user
                        if (!$item->getListId() && ($item->getAssignedContactId() != $user_id || $item->getContactId() != $user_id)) {
                            return false;
                        }

                        $filtered_items[$item->getId()] = $item;
                    }

                    break;
            }

            if ($filtered_items) {
                $item = reset($filtered_items);
                $list = $this->getList($item);
                $items_left = $item->getListId() ? count($list->getUndoneItems()) : 0;

                $emailContent = new pocketlistsNotificationEmailContent();
                $emailContent
                    ->setToContactId($contact->getId())
                    ->setToEmail($contact->getEmail())
                    ->setParams(
                        [
                            'n'        => $items_left,
                            'list'     => [
                                'id'   => (int)$list->getId(),
                                'name' => $list->getName(),
                                'url'  => $list ? sprintf(
                                    '#/pocket/%s/list/%s/',
                                    $list->getPocketId(),
                                    $list->getId()
                                ) : false,
                            ],
                            'complete' => $item->getStatus(),
                            'item'     => [
                                'name'         => $item->getName(),
                                'note' => $item->getNote(),
                                'note_parsed' => $item->getNoteParsed(),
                                'contact_name' => $item->getCompleteContact()
                                    ? $item->getCompleteContact()->getName()
                                    : '',
                            ],
                        ]
                    )
                    ->setSubject($subject)
                    ->setTemplate('templates/mails/completeanyitem.html');

                $notificationFactory->insert(
                    $notificationFactory
                        ->createNewEmail($emailContent)
                        ->setIdentifier(self::IDENTIFIER)
                );
            }
        }
    }

    /**
     * @param pocketlistsItem $item
     * @param                 $user_id
     *
     * @return bool
     * @throws waException
     */
    private function checkCompleteItem(pocketlistsItem $item, $user_id)
    {
        // completed not by user or not by user, but item is assigned to user
        if ($item->getCompleteContactId() == $user_id || ($item->getAssignedContactId() == $user_id && $item->getCompleteContactId() == $user_id)) {
            return false;
        }

        $list = $this->getList($item);
        if ($item->getListId() && !pocketlistsRBAC::canAccessToList($list, $user_id)) {
            return false;
        }

        return true;
    }
}
