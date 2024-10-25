<?php

class pocketlistsCommentsDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $this->http_status_code = 204;
        $comment_ids = $this->get('id');

        if (empty($comment_ids)) {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'id', 400));
        } elseif (!is_array($comment_ids)) {
            throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'id'), 400);
        }

        /** @var pocketlistsCommentFactory $plf */
        $plf = pl2()->getEntityFactory(pocketlistsComment::class);
        $comment_ids = array_unique($comment_ids);

        $logs = [];
        $comments = $plf->findById($comment_ids);
        foreach ($comments as $comment) {
            $plf->delete($comment);
            $logs[] = [
                'id'         => $comment->getId(),
                'item_id'    => $comment->getItemId(),
                'list_id'    => $comment->getListId(),
                'pocket_id'  => $comment->getPocketId(),
                'contact_id' => $comment->getContactId(),
                'comment'    => $comment->getComment(),
            ];
        }
        $this->saveLog(
            pocketlistsLog::ENTITY_COMMENT,
            pocketlistsLog::ACTION_DELETE,
            $logs
        );
    }
}
