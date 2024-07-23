<?php

class pocketlistsCommentDeleteMethod extends pocketlistsApiAbstractMethod
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

        $comments = $plf->findByFields('id', $comment_ids, true);
        foreach ($comments as $comment) {
            $plf->delete($comment);
        }
    }
}
