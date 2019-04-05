<?php

/**
 * Class pocketlistsAttachmentFactory
 *
 * @method pocketlistsAttachmentModel getModel()
 */
class pocketlistsAttachmentFactory extends pocketlistsFactory
{
    /**
     * @param pocketlistsItem $item
     *
     * @return bool|void
     * @throws waException
     */
    public function deleteAllByItem(pocketlistsItem $item)
    {
        return $this->getModel()->remove($item->getId());
    }
}