<?php

/**
 * Class pocketlistsNotificationModel
 */
class pocketlistsNotificationModel extends waModel
{
    /**
     * @var string
     */
    protected $table = 'pocketlists_notification';

    /**
     * @param int $limit
     *
     * @return array
     */
    public function getUnsent($limit)
    {
        return $this
            ->select('*')
            ->where(
                'status = i:status AND sent_at is null',
                ['status' => pocketlistsNotification::STATUS_PENDING]
            )
            ->order('id')
            ->limit($limit)
            ->fetchAll();
    }
}
