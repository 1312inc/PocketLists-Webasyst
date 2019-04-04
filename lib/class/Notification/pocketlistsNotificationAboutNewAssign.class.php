<?php

/**
 * Class pocketlistsNotificationsAboutNewItems
 */
class pocketlistsNotificationAboutNewAssign extends pocketlistsNotification
{
    /**
     * Notify all related users about new items (according to their settings)
     *
     * @param pocketlistsItem $item
     * @param string          $by_username
     *
     * @throws waException
     */
    public function notify(pocketlistsItem $item, $by_username = '')
    {
        if (!$by_username) {
            $by_username = wa()->getUser()->getName();
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
        if (!$this->canSend($contact)) {
            return;
        }

        /** @var pocketlistsItem $item */
        $item = new pocketlistsItemOutputDecorator($item);
        $this->sendMail(
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
            $this->getBackendUrl($item->getAssignedContactId())
        );
    }
}
