<?php

/**
 * Trait pocketlistsViewTrait
 */
trait pocketlistsViewTrait
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
     * @throws pocketlistsForbiddenException
     * @throws waException
     */
    public function preExecute()
    {
        $this->user = pl2()->getUser();

        if (!pocketlistsRBAC::canAccess()) {
            throw new pocketlistsForbiddenException();
        }

        $this->logService = pl2()->getLogService();
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
                ?: waRequest::request('item_id', 0, waRequest::TYPE_INT)
                    ?: waRequest::request('list_id', 0, waRequest::TYPE_INT)
                        ?: waRequest::request('pocket_id', 0, waRequest::TYPE_INT);

        if (!$id) {
            throw new pocketlistsNotFoundException();
        }

        return $id;
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
}