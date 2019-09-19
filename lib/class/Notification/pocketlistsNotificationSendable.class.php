<?php

/**
 * Interface pocketlistsNotificationSendable
 */
interface pocketlistsNotificationSendable
{
    /**
     * @param pocketlistsNotification $notification
     *
     * @return mixed
     */
    public function send(pocketlistsNotification $notification);
}
