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
     * @param int $limit
     * @param int $contactId
     *
     * @return array
     */
    public function getUnsent($limit, $contactId = 0)
    {
        return $this
            ->select('*')
            ->where(
                sprintf('status = i:status AND sent_at is null%s', $contactId ? ' and contact_id = i:contact_id' : ''),
                ['status' => pocketlistsNotification::STATUS_PENDING,
                'contact_id' => $contactId]
            )
            ->order('id')
            ->limit($limit)
            ->fetchAll();
    }
}
