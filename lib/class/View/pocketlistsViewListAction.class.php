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
        $id = $id ?: waRequest::request('list_id', 0, waRequest::TYPE_INT);
        if (!$id) {
            throw new pocketlistsNotFoundException();
        }

        $item = pl2()->getEntityFactory(pocketlistsList::class)->findById($id);
        if (!$item) {
            throw new pocketlistsNotFoundException();
        }

        return $item;
    }
}
