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
        $this->user = pl2()->getUser();

        if (!pocketlistsRBAC::canAccess()) {
            throw new waException('Access denied.', 403);
        }
    }
}
