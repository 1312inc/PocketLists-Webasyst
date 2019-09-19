<?php

/**
 * Class pocketlistsNotificationModel
 */
class pocketlistsNotificationModel extends pocketlistsModel
{
    /**
     * @var string
     */
    protected $table = 'pocketlists_notification';

    /**
     * get all external notifications
     *
     * @param int $limit
     * @param int $contactId
     *
     * @return array
     */
    public function getExternalUnsent($limit)
    {
        return $this
            ->select('*')
            ->where(
                'status = i:status AND sent_at is null and direction = s:direction',
                [
                    'status'    => pocketlistsNotification::STATUS_PENDING,
                    'direction' => pocketlistsNotification::DIRECTION_EXTERNAL,
                ]
            )
            ->order('id')
            ->limit($limit)
            ->fetchAll();
    }

    /**
     * get all external notifications
     *
     * @param int $limit
     * @param int $contactId
     *
     * @return array
     */
    public function getInternalUnsentForUser($limit, $contactId)
    {
        return $this
            ->select('*')
            ->where(
                'status = i:status AND sent_at is null and direction = s:direction and contact_id = i:contact_id',
                [
                    'status'     => pocketlistsNotification::STATUS_PENDING,
                    'direction'  => pocketlistsNotification::DIRECTION_INTERNAL,
                    'contact_id' => $contactId,
                ]
            )
            ->order('id')
            ->limit($limit)
            ->fetchAll();
    }
}
