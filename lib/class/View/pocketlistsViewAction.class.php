<?php

/**
 * Class pocketlistsViewAction
 */
class pocketlistsViewAction extends waViewAction
{
    /**
     * @var pocketlistsUser
     */
    protected $user;

    /**
     * @throws waException
     */
    public function preExecute()
    {
        $this->user = wa(pocketlistsHelper::APP_ID)->getConfig()->getUser();

        if (!pocketlistsRBAC::canAccess()) {
            throw new waException('Access denied.', 403);
        }
    }
}
