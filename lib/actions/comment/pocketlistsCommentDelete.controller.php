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
                $im = new pocketlistsItemModel();
                $item = $im->getById($comment['item_id']);

                if (!empty($item['list_id']) && !pocketlistsRBAC::canAccessToList($item['list_id'])) {
                    throw new waException('Access denied.', 403);
                }

                if ((time() - strtotime($comment['create_datetime']) < 60 * 60 * 24)
                    && $comment['contact_id'] === wa()->getUser()->getId()
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
