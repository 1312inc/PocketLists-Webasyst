<?php

/**
 * Class pocketlistsAttachmentFactory
 *
 * @method pocketlistsNotificationModel getModel()
 */
class pocketlistsNotificationFactory extends pocketlistsFactory implements pocketlistsFactoryInterface
{
    protected $entity = 'pocketlistsNotification';

    /**
     * @param pocketlistsNotificationContentInterface $content
     *
     * @return pocketlistsNotification
     */
    public function createNewEmail(pocketlistsNotificationContentInterface $content)
    {
        /** @var pocketlistsNotification $notification */
        $notification = $this->createNew();
        $notification
            ->setType(pocketlistsNotification::TYPE_EMAIL)
            ->setCreatedAt(date('Y-m-d H:i:s'))
            ->setContent($content);

        return $notification;
    }

    /**
     * @param pocketlistsNotification $notification
     *
     * @return pocketlistsNotificationContentInterface
     *
     * @throws pocketlistsNotImplementedException
     */
    public function createContentForNotification(pocketlistsNotification $notification)
    {
        switch ($notification->getType()) {
            case pocketlistsNotification::TYPE_EMAIL:
                $notificationEmailContent = new pocketlistsNotificationEmailContent($notification->getData());

                return $notificationEmailContent;

            default:
                $event = new pocketlistsEvent(pocketlistsEventStorage::CREATE_NOTIFICATION_CONTENT, $notification);

                /** @var pocketlistsNotificationContentInterface $notificationContent */
                pl2()->getEventDispatcher()->dispatch($event);
                $notificationContent = $event->getResponse();
                if ($notificationContent instanceof pocketlistsNotificationContentInterface) {
                    return $notificationContent;
                }
        }

        throw new pocketlistsNotImplementedException();
    }

    /**
     * @param int $limit
     *
     * @return pocketlistsNotification[]
     * @throws waException
     */
    public function findUnsent($limit = 100)
    {
        $data = $this->getModel()->getUnsent($limit);

        return $this->generateWithData($data, true);
    }

    /**
     * @param int $limit
     *
     * @return pocketlistsNotification[]
     * @throws waException
     */
    public function findUnsentForUser(pocketlistsContact $user = null, $limit = 10)
    {
        $user = $user ?: pl2()->getUser();
        $data = $this->getModel()->getUnsent($limit, $user->getId());

        return $this->generateWithData($data, true);
    }
}
