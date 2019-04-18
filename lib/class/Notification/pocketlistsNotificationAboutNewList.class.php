<?php

/**
 * Class pocketlistsNotificationAboutNewList
 */
class pocketlistsNotificationAboutNewList extends pocketlistsNotification
{
    /**
     * @param pocketlistsList $list
     *
     * @throws waException
     */
    public function notifyAboutNewList(pocketlistsList $list)
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
            [
                'app_id' => wa()->getApp(),
            ]
        )->fetchAll('contact_id');

        $c = $list->getContact();
        $create_contact_name = $c->getName();

        $list->setCreateDatetime(waDateTime::format('humandatetime', $list->getCreateDatetime()));

        $contactFactory = pl2()->getEntityFactory(pocketlistsContact::class);

        foreach ($users as $user_id => $user) { // foreach user
            $contact = $contactFactory->createNewWithId($user_id);
            if (!$this->canSend($contact)) {
                continue;
            }

            // created by user
            if ($list->getContactId() == $user_id) {
                continue;
            }

            if (!pocketlistsRBAC::canAccessToList($list, $user_id)) {
                continue;
            }

            $this->sendMail(
                [
                    'contact_id' => $user_id,
                    'subject'    => 'string:📝 '.$list->getName(),
                    'body'       => wa()->getAppPath('templates/mails/newlist.html'),
                    'variables'  => [
                        'list_name'       => $list->getName(),
                        'list_url'        => sprintf('#/pocket/%s/list/%s/', $list->getPocketId(), $list->getId()),
                        'by'              => $create_contact_name,
                        'create_datetime' => $list->getCreateDatetime(),
                    ],
                ],
                $this->getBackendUrl($user_id)
            );
        }
    }
}
