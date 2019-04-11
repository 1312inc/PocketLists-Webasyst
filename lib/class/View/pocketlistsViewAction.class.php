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

    /**
     * @param int $id
     *
     * @return int|mixed
     * @throws pocketlistsNotFoundException
     */
    protected function getId($id = 0)
    {
        $id = $id
            ?: waRequest::request('id', 0, waRequest::TYPE_INT)
                ?: waRequest::request('list_id', 0, waRequest::TYPE_INT)
                    ?: waRequest::request('pocket_id', 0, waRequest::TYPE_INT);

        if (!$id) {
            throw new pocketlistsNotFoundException();
        }

        return $id;
    }
}
