<?php

/**
 * Class pocketlistsAttachmentFactory
 *
 * @method pocketlistsAttachmentModel getModel()
 */
class pocketlistsAttachmentFactory extends pocketlistsFactory implements pocketlistsFactoryInterface
{
    protected $entity = 'pocketlistsAttachment';

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

    /**
     * @param pocketlistsAttachment|pocketlistsEntity $attachment
     *
     * @return bool
     * @throws waException
     */
    public function delete(pocketlistsEntity $attachment)
    {
        waFiles::delete(wa()->getDataPath('attachments/'.$attachment->getId().'/', true, pocketlistsHelper::APP_ID));

        return parent::delete($attachment);
    }
}
