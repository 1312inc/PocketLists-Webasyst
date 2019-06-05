<?php

/**
 * Class pocketlistsLogFactory
 *
 * @method pocketlistsLogModel getModel()
 * @method pocketlistsLog createNew()
 */
class pocketlistsLogFactory extends pocketlistsFactory
{
    /**
     * @var string
     */
    protected $entity = 'pocketlistsLogModel';

    /**
     * @param pocketlistsItem $item
     * @param null|array      $data
     *
     * @return pocketlistsLog
     */
    public function createNewAfterItemAdd(pocketlistsItem $item, $data = null)
    {
        $data = array_merge(
            [
                'name' => $item->getName(),
            ],
            $data
        );

        return $this->createNewItemLog($item)
            ->setAction(pocketlistsLog::ACTION_ADD)
            ->setData($data);
    }

    /**
     * @param pocketlistsItem $item
     * @param null|array      $data
     *
     * @return pocketlistsLog
     */
    public function createNewAfterItemUpdate(pocketlistsItem $item, $data = null)
    {
        $data = array_merge(
            [
                'name' => $item->getName(),
            ],
            $data
        );

        return $this->createNewItemLog($item)
            ->setAction(pocketlistsLog::ACTION_UPDATE)
            ->setData($data);
    }

    /**
     * @param pocketlistsItem $item
     * @param null|array      $data
     *
     * @return pocketlistsLog
     */
    public function createNewAfterItemDelete(pocketlistsItem $item, $data = null)
    {
        $data = array_merge(
            [
                'name' => $item->getName(),
            ],
            $data
        );

        return $this->createNewItemLog($item)
            ->setAction(pocketlistsLog::ACTION_DELETE)
            ->setData($data);
    }

    /**
     * @param pocketlistsItem       $item
     * @param pocketlistsAttachment $attachment
     * @param null|array            $data
     *
     * @return pocketlistsLog
     */
    public function createNewItemAttachmentAdd(pocketlistsItem $item, pocketlistsAttachment $attachment, $data = null)
    {
        $data = array_merge(
            [
                'name'       => $item->getName(),
                'attachment' => [
                    'filename' => $attachment->getFilename(),
                    'type'     => $attachment->getFiletype(),
                ],
            ],
            $data
        );

        return $this->createNewItemLog($item)
            ->setAction(pocketlistsLog::ACTION_ATTACHMENT_ADD)
            ->setData($data);
    }

    /**
     * @param pocketlistsItem $item
     *
     * @return pocketlistsLog
     */
    public function createNewItemLog(pocketlistsItem $item)
    {
        return $this->createNew()
            ->setEntityType(pocketlistsLog::ENTITY_ITEM)
            ->setEntityId($item->getId())
            ->setCreatedDatetime(date('Y-m-d H:i:s'));
    }

}
