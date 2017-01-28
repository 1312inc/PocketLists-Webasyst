<?php

class pocketlistsCommentDeleteController extends waJsonController
{
    public function execute()
    {
        $comment_id = waRequest::post('id', 0, waRequest::TYPE_INT);

        if ($comment_id) {
            $cm = new pocketlistsCommentModel();
            $comment = $cm->getById($comment_id);
            if ($comment) {
                if ($comment['contact_id'] === wa()->getUser()->getId()
                    && (time() - strtotime($comment['create_datetime']) < 60 * 10)
                    && $cm->deleteById($comment_id)
                ) {
                    $this->response = 'ok';
                } else {
                    $this->errors = 'error while deleting item comment';
                }
            } else {
                $this->errors = 'no comment with such id';
            }
        } else {
            $this->errors = 'no id';
        }
    }
}
