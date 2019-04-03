<?php

/**
 * Class pocketlistsComplete
 */
class pocketlistsComplete extends pocketlistsJsonController
{
    /**
     * @var pocketlistsItem[]
     */
    protected $completed_items = [];

    /**
     * @param pocketlistsItem $item
     * @param                 $status
     *
     * @throws waException
     */
    protected function changeComplete(pocketlistsItem $item, $status)
    {
        if ($status) {
            $item->setDone();
        } else {
            $item->setUndone();
        }

        if (pl2()->getEntityFactory(pocketlistsItem::class)->save($item)) {
            $this->completed_items[] = $item;
        } else {
            $this->errors[] = 'error while updating parent id: ' . $item->getId();
        }
    }
}
