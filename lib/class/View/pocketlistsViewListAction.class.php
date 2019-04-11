<?php

/**
 * Class pocketlistsViewListAction
 */
class pocketlistsViewListAction extends pocketlistsViewAction
{
    /**
     * @param bool $id
     *
     * @return pocketlistsList
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
