<?php

/**
 * Class pocketlistsDebugRecapController
 */
class pocketlistsDebugRecapController extends waJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        if (!pocketlistsRBAC::isAdmin()) {
            throw new waException('Access denied.', 403);
        }

        if (!waSystemConfig::isDebug()) {
            throw new waException('Not debug mode.', 403);
        }

        (new pocketlistsNotificationDailyRecap())->notify(array(), true);

        $this->response = 'ok';
    }
}
