<?php

class pocketlistsCommentAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $_json = $this->readBodyAsJson();
        $item_id = ifset($_json, 'item_id', null);
        $comment_text = ifset($_json, 'comment', null);

        if (!isset($item_id)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'item_id'), 400);
        } elseif (!is_numeric($item_id)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'item_id'), 400);
        } elseif ($item_id < 1) {
            throw new waAPIException('not_found', _w('Item not found'), 404);
        }

        if (!isset($comment_text)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'comment'), 400);
        } elseif (!is_string($comment_text)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'comment'), 400);
        }


        /** @var pocketlistsItemFactory $item_factory */
        $item_factory = pl2()->getEntityFactory(pocketlistsItem::class);

        /** @var pocketlistsItem $item */
        $item = $item_factory->findById($item_id);

        if ($item->getListId() === null) {
            throw new waAPIException('not_found', _w('List not found'), 404);
        } elseif (!pocketlistsRBAC::canAccessToList($item->getList())) {
            throw new waAPIException('forbidden', _w('Access denied'), 403);
        }

        /** @var pocketlistsCommentFactory $comment_factory */
        $comment_factory = pl2()->getEntityFactory(pocketlistsComment::class);

        /** @var pocketlistsComment $comment */
        $comment = $comment_factory->createNew();
        $comment->setItem($item)
            ->setContactId(wa()->getUser()->getId())
            ->setComment($comment_text)
            ->setCreateDatetime(date('Y-m-d H:i:s'));

        if (!$comment_factory->insert($comment)) {
            throw new waAPIException('type_error', _w('Error while adding new item comment'), 400);
        }

        $this->response = [
            'id' => $comment->getId()
        ];
    }
}