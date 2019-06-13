<?php

/**
 * Class pocketlistsViewAction
 */
abstract class pocketlistsViewAction extends waViewAction
{
    /**
     * @var pocketlistsUser
     */
    protected $user;

    /**
     * @var pocketlistsLogService
     */
    protected $logService;

    /**
     * @param null|array $params
     *
     * @return mixed
     */
    abstract public function runAction($params = null);

    /**
     * @throws pocketlistsForbiddenException
     * @throws waException
     */
    public function preExecute()
    {
        $this->user = pl2()->getUser();
        $this->logService = pl2()->getLogService();
    }

    /**
     * @param null $params
     *
     * @throws waException
     */
    public function execute($params = null)
    {
        try {
            if (!pocketlistsRBAC::canAccess()) {
                throw new pocketlistsForbiddenException();
            }

            $this->view->assign([
                'backend_url'          => pl2()->getBackendUrl(true),
                'plurl'                => wa()->getAppUrl(pocketlistsHelper::APP_ID),
                'current_user'         => pl2()->getUser(),
                'pl2_attachments_path' => wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID),
            ]);

            $this->runAction($params);
        } catch (pocketlistsException $ex) {
            $this->view->assign(
                'error',
                [
                    'code'    => $ex->getCode(),
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
