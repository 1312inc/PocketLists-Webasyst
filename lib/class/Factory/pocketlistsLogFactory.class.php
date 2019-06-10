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
        return $this->createNewItemLog($context, pocketlistsLog::ACTION_ADD);
    }

    /**
     * @param pocketlistsLogContext $context
     *
     * @return pocketlistsLog
     * @throws pocketlistsLogicException
     */
    public function createNewAfterItemAssign(pocketlistsLogContext $context)
    {
        return $this->createNewItemLog($context, pocketlistsLog::ACTION_ASSIGN);
    }

    /**
     * @param pocketlistsLogContext $context
     *
     * @return pocketlistsLog
     * @throws pocketlistsLogicException
     */
    public function createNewAfterItemUpdate(pocketlistsLogContext $context)
    {
        return $this->createNewItemLog($context, pocketlistsLog::ACTION_UPDATE);
    }

    /**
     * @param pocketlistsLogContext $context
     *
     * @return pocketlistsLog
     * @throws pocketlistsLogicException
     */
    public function createNewAfterItemDelete(pocketlistsLogContext $context)
    {
        return $this->createNewItemLog($context, pocketlistsLog::ACTION_DELETE);
    }

    /**
     * @param pocketlistsLogContext $context
     * @param string                $action
     *
     * @return pocketlistsLog
     * @throws pocketlistsLogicException
     */
    public function createNewItemLog(pocketlistsLogContext $context, $action = pocketlistsLog::ACTION_ADD)
    {
        /** @var pocketlistsItem $item */
        $item = $context->getEntity(pocketlistsLogContext::ITEM_ENTITY);

        $params = [
            'item' => [
                'name' => $item->getName(),
            ],
        ];

        return $this->createNew()
            ->setEntityType(pocketlistsLog::ENTITY_ITEM)
            ->setCreatedDatetime(date('Y-m-d H:i:s'))
            ->setContactId(pl2()->getUser()->getId())
            ->setAction($action)
            ->setParams($params)
            ->fillWithContext($context);
    }

    /**
     * @param pocketlistsLogContext $context
     * @param string                $action
     *
     * @return pocketlistsLog
     * @throws pocketlistsLogicException
     */
    public function createNewAttachmentLog(pocketlistsLogContext $context, $action = pocketlistsLog::ACTION_ADD)
    {
        /** @var pocketlistsItem $item */
        $item = $context->getEntity(pocketlistsLogContext::ITEM_ENTITY);

        /** @var pocketlistsAttachment $attachment */
        $attachment = $context->getEntity(pocketlistsLogContext::ATTACHMENT_ENTITY);

        $context->addParam(
            [
                'item'       => [
                    'name' => $item->getName(),
                ],
                'attachment' => [
                    'filename' => $attachment->getFilename(),
                    'type'     => $attachment->getFiletype(),
                ],
            ]
        );

        return $this->createNew()
            ->setEntityType(pocketlistsLog::ENTITY_ATTACHMENT)
            ->setCreatedDatetime(date('Y-m-d H:i:s'))
            ->setAction($action)
            ->setContactId(pl2()->getUser()->getId())
            ->fillWithContext($context);
    }

    /**
     * @param pocketlistsLogContext $context
     * @param string                $action
     *
     * @return pocketlistsLog
     * @throws pocketlistsLogicException
     */
    public function createNewListLog(pocketlistsLogContext $context, $action = pocketlistsLog::ACTION_ADD)
    {
        /** @var pocketlistsList $list */
        $list = $context->getEntity(pocketlistsLogContext::LIST_ENTITY);

        $context->addParam(
            [
                'list' => [
                    'name' => $list->getName(),
                ],
            ]
        );

        return $this->createNew()
            ->setEntityType(pocketlistsLog::ENTITY_LIST)
            ->setCreatedDatetime(date('Y-m-d H:i:s'))
            ->setAction($action)
            ->setContactId(pl2()->getUser()->getId())
            ->fillWithContext($context);
    }

    /**
     * @param pocketlistsLogContext $context
     * @param string                $action
     *
     * @return pocketlistsLog
     * @throws pocketlistsLogicException
     */
    public function createNewPocketLog(pocketlistsLogContext $context, $action = pocketlistsLog::ACTION_ADD)
    {
        /** @var pocketlistsPocket $pocket */
        $pocket = $context->getEntity(pocketlistsLogContext::POCKET_ENTITY);

        $context->addParam(
            [
                'pocket' => [
                    'name' => $pocket->getName(),
                ],
            ]
        );

        return $this->createNew()
            ->setEntityType(pocketlistsLog::ENTITY_POCKET)
            ->setCreatedDatetime(date('Y-m-d H:i:s'))
            ->setAction($action)
            ->setContactId(pl2()->getUser()->getId())
            ->fillWithContext($context);
    }

    /**
     * @param pocketlistsLogContext $context
     * @param string                $action
     *
     * @return pocketlistsLog
     * @throws pocketlistsLogicException
     */
    public function createNewCommentLog(pocketlistsLogContext $context, $action = pocketlistsLog::ACTION_ADD)
    {
        /** @var pocketlistsComment $comment */
        $comment = $context->getEntity(pocketlistsLogContext::COMMENT_ENTITY);

        /** @var pocketlistsItem $item */
        $item = $context->getEntity(pocketlistsLogContext::ITEM_ENTITY);

        $params = [
            'item' => [
                'name' => $item->getName(),
            ],
        ];

        if ($action === pocketlistsLog::ACTION_ADD) {
            $params['comment'] = [
                'comment' => $comment->getComment(),
            ];
        }

        return $this->createNew()
            ->setEntityType(pocketlistsLog::ENTITY_COMMENT)
            ->setCreatedDatetime(date('Y-m-d H:i:s'))
            ->setAction($action)
            ->setContactId(pl2()->getUser()->getId())
            ->setParams($params)
            ->fillWithContext($context);
    }
}
