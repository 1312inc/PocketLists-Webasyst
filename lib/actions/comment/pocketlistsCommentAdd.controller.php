<?php

class pocketlistsCommentAddController extends waJsonController
{
    public function execute()
    {
        $item_id = waRequest::post('item_id', 0, waRequest::TYPE_ARRAY);
        $comment = waRequest::post('comment', false, waRequest::TYPE_STRING_TRIM);

        if ($item_id && $comment) {
            $cm = new pocketlistsCommentModel();

            $last_id = $cm->insert(array(
                'item_id' => $item_id,
                'contact_id' => wa()->getUser()->getId(),
                'comment' => $comment,
                'create_datetime' => date('Y-m-d H:i:s')
            ));
            if ($last_id) {
                $this->response = $last_id;
            } else {
                $this->errors = 'error while adding new item comment';
            }
        }
    }
}
