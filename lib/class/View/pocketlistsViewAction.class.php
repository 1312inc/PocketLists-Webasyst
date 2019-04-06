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

    /**
     * @param null $params
     */
    public function run($params = null)
    {
        try {
            $this->preExecute();
            $this->execute();
            $this->afterExecute();
        } catch (waException $ex) {
            $this->view->assign(
                'error',
                [
                    'code'    => 403,
                    'message' => $ex->getMessage(),
                ]
            );

            $this->setTemplate('templates/include/error.html');
        }
    }
}
