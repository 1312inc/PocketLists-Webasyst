<?php

class pocketlistsCommentAddController extends waJsonController
{
    public function execute()
    {
        $item_id = waRequest::post('item_id', 0, waRequest::TYPE_INT);
        $comment = waRequest::post('comment', false, waRequest::TYPE_STRING_TRIM);

        if ($item_id && $comment) {
            $im = new pocketlistsItemModel();
            $item = $im->getById($item_id);
            if ($item && $item['status'] == 0) {
                $cm = new pocketlistsCommentModel();

                $user = wa()->getUser();
                $insert_data = array(
                    'item_id' => $item['id'],
                    'contact_id' => $user->getId(),
                    'comment' => $comment,
                    'create_datetime' => date('Y-m-d H:i:s')
                );

                $last_id = $cm->insert($insert_data);
                if ($last_id) {
                    $this->response = array(
                        'datetime' => waDateTime::format("humandatetime", $insert_data['create_datetime']),
                        'username' => htmlspecialchars($user->getName(), ENT_QUOTES),
                        'comment' => nl2br(htmlspecialchars($insert_data['comment'], ENT_QUOTES))
                    );
                    pocketlistsNotifications::notifyAboutNewComment($insert_data);
                } else {
                    $this->errors = 'error while adding new item comment';
                }
            }
        }
    }
}
