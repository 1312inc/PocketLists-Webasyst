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

    /**
     * @param pocketlistsItem $item
     * @param array           $names
     *
     * @return bool|void
     * @throws waException
     */
    public function deleteAllByItemAndNames(pocketlistsItem $item, array $names)
    {
        return $this->getModel()->remove($item->getId(), $names);
    }
}
