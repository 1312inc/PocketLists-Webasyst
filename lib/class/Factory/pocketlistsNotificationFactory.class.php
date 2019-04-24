<?php

/**
 * Class pocketlistsAttachmentFactory
 *
 * @method pocketlistsNotificationModel getModel()
 */
class pocketlistsNotificationFactory extends pocketlistsFactory implements pocketlistsFactoryInterface
{
    protected $entity;

    /**
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
                $obj = new pocketlistsNotificationEmailContent();
                $obj->extractJson($notification->getData());

                break;

            default:
                throw new pocketlistsNotImplementedException();
        }

        return $obj;
    }
}
