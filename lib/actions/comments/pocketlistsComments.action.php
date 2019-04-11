<?php

/**
 * Class pocketlistsCommentsAction
 */
class pocketlistsCommentsAction extends waViewAction
{
    const DEFAULT_OFFSET = 50;

    private $last_activity;

    public function execute()
    {
        $this->last_activity = pocketlistsActivity::getUserActivity();
        $offset = waRequest::get('offset', 0);

        /** @var pocketlistsCommentFactory $commentFactory */
        $commentFactory = pl2()->getEntityFactory(pocketlistsComment::class);
        $comments = $commentFactory->findForPage($offset * self::DEFAULT_OFFSET, self::DEFAULT_OFFSET);

        /** @var pocketlistsComment $comment */
        foreach ($comments as $comment) {
            $comment = new pocketlistsCommentOutputDecorator($comment);
            $comment->setRecentlyCreated($this->last_activity);
        }

        $this->view->assign('comments', $comments);
    }
}
