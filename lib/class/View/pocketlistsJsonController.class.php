<?php

/**
 * Class pocketlistsViewAction
 */
class pocketlistsJsonController extends waJsonController
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
     * @param null $params
     */
    public function run($params = null)
    {
        $this->user = pl2()->getUser();
        $this->logService = pl2()->getLogService();

        try {
            $this->preExecute();
            $this->execute();
            $this->afterExecute();
        } catch (waException $ex) {
            $this->errors = $ex->getMessage();
        }

        $this->display();
    }

    /**
     * @param int $id
     *
     * @return pocketlistsItem
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    protected function getItem($id = 0)
    {
        $item = pl2()->getEntityFactory(pocketlistsItem::class)->findById($this->getId($id));
        if (!$item) {
            throw new pocketlistsNotFoundException();
        }

        return $item;
    }

    /**
     * @param int $id
     *
     * @return pocketlistsList
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    protected function getList($id = 0)
    {
        $list = pl2()->getEntityFactory(pocketlistsList::class)->findById($this->getId($id));
        if (!$list) {
            throw new pocketlistsNotFoundException();
        }

        return $list;
    }

    /**
     * @param int $id
     *
     * @return pocketlistsPocket
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    protected function getPocket($id = 0)
    {
        $list = pl2()->getEntityFactory(pocketlistsPocket::class)->findById($this->getId($id));
        if (!$list) {
            throw new pocketlistsNotFoundException();
        }

        return $list;
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
