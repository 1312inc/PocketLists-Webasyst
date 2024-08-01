<?php

class pocketlistsCommentUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $_json = $this->readBodyAsJson();
        $comment_id = ifset($_json, 'id', null);
        $comment_text = ifset($_json, 'comment', null);

        if (!isset($comment_id)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'id'), 400);
        } elseif (!isset($comment_text)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'comment'), 400);
        } elseif (!is_numeric($comment_id)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'id'), 400);
        } elseif (!is_string($comment_text)) {
            throw new waAPIException('type_error', sprintf_wp('Type error parameter: “%s”.', 'comment'), 400);
        } elseif ($comment_id < 1 || !$comment = pl2()->getEntityFactory(pocketlistsComment::class)->findById($comment_id)) {
            throw new waAPIException('not_found', _w('Comment not found'), 404);
        }

        /** @var pocketlistsCommentFactory $comment_factory */
        $comment_factory = pl2()->getEntityFactory(pocketlistsComment::class);
        $comment->setComment($comment_text);
        $saved = $comment_factory->save($comment);
        if (!$saved) {
            throw new waAPIException('error', _w('Some error on save comment'), 500);
        }

        $this->response = $this->filterFields([[
            'id'              => $comment->getId(),
            'item_id'         => $comment->getItemId(),
            'contact_id'      => $comment->getContactId(),
            'comment'         => $comment->getComment(),
            'create_datetime' => $comment->getCreateDatetime(),
            'uuid'            => $comment->getUuid(),
        ]], [
            'id',
            'item_id',
            'contact_id',
            'comment',
            'create_datetime',
            'uuid',
        ], [
            'id' => 'int',
            'item_id' => 'int',
            'contact_id' => 'int',
            'create_datetime' => 'datetime',
        ]);
        $this->response = reset($this->response);
    }
}
