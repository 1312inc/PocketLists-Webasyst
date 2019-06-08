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
     * @param pocketlistsLogContext $context
     *
     * @return pocketlistsLog
     * @throws pocketlistsLogicException
     */
    public function createNewAfterItemAdd(pocketlistsLogContext $context)
    {
        /** @var pocketlistsItem $item */
        $item = $context->getEntity(pocketlistsLogContext::ITEM_ENTITY);

        return $this->createNewItemLog($item)
            ->setAction(pocketlistsLog::ACTION_ADD)
            ->fillWithContext($context);
    }

    /**
     * @param pocketlistsLogContext $context
     *
     * @return pocketlistsLog
     * @throws pocketlistsLogicException
     */
    public function createNewAfterItemUpdate(pocketlistsLogContext $context)
    {
        /** @var pocketlistsItem $item */
        $item = $context->getEntity(pocketlistsLogContext::ITEM_ENTITY);

        return $this->createNewItemLog($item)
            ->setAction(pocketlistsLog::ACTION_UPDATE)
            ->fillWithContext($context);
    }

    /**
     * @param pocketlistsLogContext $context
     *
     * @return pocketlistsLog
     * @throws pocketlistsLogicException
     */
    public function createNewAfterItemDelete(pocketlistsLogContext $context)
    {
        /** @var pocketlistsItem $item */
        $item = $context->getEntity(pocketlistsLogContext::ITEM_ENTITY);

        return $this->createNewItemLog($item)
            ->setAction(pocketlistsLog::ACTION_DELETE)
            ->fillWithContext($context);
    }

    /**
     * @param pocketlistsLogContext $context
     *
     * @return pocketlistsLog
     * @throws pocketlistsLogicException
     */
    public function createNewItemAttachmentAdd(pocketlistsLogContext $context)
    {
        /** @var pocketlistsItem $item */
        $item = $context->getEntity(pocketlistsLogContext::ITEM_ENTITY);

        /** @var pocketlistsAttachment $attachment */
        $attachment = $context->getEntity(pocketlistsLogContext::ATTACHMENT_ENTITY);

        $context->addParam(
            [
                'attachment' => [
                    'filename' => $attachment->getFilename(),
                    'type'     => $attachment->getFiletype(),
                ],
            ]
        );

        return $this->createNewItemLog($item)
            ->setAction(pocketlistsLog::ACTION_ATTACHMENT_ADD)
            ->fillWithContext($context);
    }

    /**
     * @param pocketlistsItem $item
     *
     * @return pocketlistsLog
     */
    public function createNewItemLog(pocketlistsItem $item)
    {
        $params = [
            'item' => [
                'name' => $item->getName(),
            ],
        ];

        return $this->createNew()
            ->setEntityType(pocketlistsLog::ENTITY_ITEM)
            ->setCreatedDatetime(date('Y-m-d H:i:s'))
            ->setContactId(pl2()->getUser()->getId())
            ->setParams($params);
    }
}
