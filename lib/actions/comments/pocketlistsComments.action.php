<?php

class pocketlistsCommentsAction extends waViewAction
{
    private $last_activity;
    const DEFAULT_OFFSET = 50;


    public function execute()
    {
        $this->last_activity = pocketlistsActivity::getUserActivity();

        $offset = waRequest::get('offset', 0);

        $comment_model = new pocketlistsCommentModel();
        $comments = $comment_model->getComments($offset * self::DEFAULT_OFFSET, self::DEFAULT_OFFSET);
        $comments = array_map(array($this, 'markAsNewAndMatchLinks'), $comments);
        $this->view->assign('comments', $comments);

//        pocketlistsActivity::setUserActivity(wa()->getUser()->getId(), true);
    }

    private function markAsNewAndMatchLinks($comment)
    {
        $comment['new'] = strtotime($comment['create_datetime']) > strtotime($this->last_activity);
        $comment['comment'] = pocketlistsNaturalInput::matchLinks($comment['comment']);

        return $comment;
    }
}
