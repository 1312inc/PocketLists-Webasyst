<?php

/**
 * Class pocketlistsViewAction
 */
class pocketlistsViewItemAction extends pocketlistsViewAction
{
    /**
     * @param bool $id
     *
     * @return pocketlistsItem
     * @throws waException
     */
    protected function getItem($id = false)
    {
        $id = $id ?: waRequest::request('id', 0, waRequest::TYPE_INT);
        if (!$id) {
            throw new waException('Item not found', 404);
        }

        $item = pl2()->getEntityFactory(pocketlistsItem::class)->findById($id);
        if (!$item) {
            throw new waException('Item not found', 404);
        }

        return $item;
    }
}
