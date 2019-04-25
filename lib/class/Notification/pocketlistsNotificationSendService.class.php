<?php

/**
 * Class pocketlistsNotificationSendService
 */
class pocketlistsNotificationSendService
{
    /**
     * @param pocketlistsNotification $notification
     *
     * @throws pocketlistsNotImplementedException
     * @throws waException
     */
    public function send(pocketlistsNotification $notification)
    {
        $content = $notification->getContent();

        try {
            if ($content->send()) {
                $notification
                    ->setSentAt(date('Y-m-d H:i:s'))
                    ->setStatus(pocketlistsNotification::STATUS_OK);
            } else {
                $notification
                    ->setError($content->getError())
                    ->setStatus(pocketlistsNotification::STATUS_FAIL);
            }
        } catch (Exception $ex) {
            $notification
                ->setError($ex->getMessage())
                ->setStatus(pocketlistsNotification::STATUS_FAIL);
        }

        pl2()->getEntityFactory(pocketlistsNotification::class)->update($notification);
    }

    /**
     * @param int $chunk
     *
     * @return int
     * @throws pocketlistsNotImplementedException
     * @throws waException
     */
    public function sendBatch($chunk = 100)
    {
        $notifications = pl2()->getEntityFactory(pocketlistsNotification::class)->findUnsent($chunk);

        $sentCount = 0;
        foreach ($notifications as $notification) {
            $this->send($notification);

            if ($notification->getStatus() === pocketlistsNotification::STATUS_OK) {
                $sentCount++;
            }
        }

        return $sentCount;
    }
}
