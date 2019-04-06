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
     * @param pocketlistsItem|pocketlistsList $item
     * @param                 $status
     *
     * @throws waException
     */
    protected function changeComplete($item, $status)
    {
        if ($status) {
            $item->setDone();
        } else {
            $item->setUndone();
        }

        if (pl2()->getEntityFactory(get_class($item))->save($item)) {
            $this->completed_items[] = $item;
        } else {
            $this->errors[] = 'error while updating parent id: ' . $item->getId();
        }
    }
}
