<?php

/**
 * Class pocketlistsViewListAction
 */
class pocketlistsViewListAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     */
    public function runAction($params = null) {}

    /**
     * @param bool $id
     *
     * @return array|mixed
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    protected function getList($id = false)
    {
        $item = pl2()->getEntityFactory(pocketlistsList::class)->findById($this->getId($id));
        if (!$item) {
            throw new pocketlistsNotFoundException();
        }

        return $item;
    }
}
