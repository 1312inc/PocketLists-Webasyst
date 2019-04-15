<?php

/**
 * Class pocketlistsNotificationsAboutNewItems
 */
class pocketlistsNotificationAboutNewItems extends pocketlistsNotification
{
    /**
     * Notify all related users about new items (according to their settings)
     *
     * @param pocketlistsItem[]    $items
     * @param pocketlistsList|null $list
     *
     * @throws waDbException
     * @throws waException
     */
    public function notify($items, pocketlistsList $list = null)
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
                AND cs2.name = 'email_add_item'
                AND cs2.value IN (i:setting)
              WHERE
                cs1.app_id = s:app_id
                AND cs1.name = 'email_add_item_on'
                AND cs1.value = 1";
        $users = $csm->query(
            $q,
            [
                'app_id'  => wa()->getApp(),
                'setting' => [
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_FAVORITE_LIST,
                    pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_ANY_LIST,
                ],
            ]
        )->fetchAll('contact_id');

        /** @var pocketlistsUserFavoritesModel $ufm */
        $ufm = pl2()->getModel('pocketlistsUserFavorites');
        $contactFactory = pl2()->getEntityFactory(pocketlistsContact::class);

        foreach ($users as $user_id => $user) { // foreach user
            $contact = $contactFactory->createNewWithId($user_id);
            if (!$this->canSend($contact)) {
                continue;
            }

            $filtered_items = [];
            $emailParams = [];

            switch ($user['setting']) {
                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_FAVORITE_LIST:
                    $user_lists = $ufm->query(
                        "SELECT i.key_list_id FROM {$ufm->getTableName()} uf JOIN pocketlists_item i ON uf.item_id = i.id AND i.key_list_id > 0 WHERE uf.contact_id = {$user_id}"
                    )->fetchAll('key_list_id');
                    $user_lists = array_keys($user_lists);

                    foreach ($items as $item) {
                        if (!$this->checkItem($item, $user_id)) {
                            continue;
                        }

                        $filtered_items[$item->getId()] = $item;
                    }

                    break;

                case pocketlistsUserSettings::EMAIL_WHEN_SOMEONE_ADDS_ITEM_TO_ANY_LIST:
                    foreach ($items as $item) {
                        if (!$this->checkItem($item, $user_id)) {
                            continue;
                        }

                        $filtered_items[$item->getId()] = $item;
                    }

                    break;
            }

            if ($filtered_items && $list) {
                $emailParams = [
                    'subject'    => 'string:{str_replace(array("\r", "\n"), " ", $item->getName()|escape)|truncate:64}',
                    'body'       => wa()->getAppPath('templates/mails/newitem.html'),
                    'contact_id' => $user_id,
                    'variables'  => [
                        'list_name' => $list->getId() ? $list->getName() : false,
                        'list_url'  => $list ? sprintf(
                            '#/pocket/%s/list/%s/',
                            $list->getPocketId(),
                            $list->getId()
                        ) : '',
                        'items'     => $filtered_items,
                        'item'      => reset($filtered_items),
                    ],
                ];

                $this->sendMail($emailParams, $this->getBackendUrl($user_id));
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
    private function checkItem(pocketlistsItem $item, $user_id)
    {
        // created not by this user
        if ($item->getContactId() == $user_id) {
            return false;
        }

        $list = $this->getList($item);

        if($list->getId() && !pocketlistsRBAC::canAccessToList($list, $user_id)) {
            return false;
        }

        // from NULL-list but not assigned to this user nor created by user
        if (!$list->getId() && ($item->getAssignedContactId() != $user_id || $item->getContactId() != $user_id)) {
            return false;
        }

        return true;
    }
}