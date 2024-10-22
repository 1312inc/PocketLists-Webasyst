<?php

class pocketlistsCommentUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PATCH;

    public function execute()
    {

        $comments = $this->readBodyAsJson();
        if (empty($comments)) {
            throw new waAPIException('required_param', _w('Missing data'), 400);
        } elseif (!is_array($comments)) {
            throw new waAPIException('type_error', _w('Type error data'), 400);
        }

        $comment_ids = array_unique(array_column($comments, 'id'));
        if (empty($comment_ids)) {
            throw new waAPIException('type_error', _w('Type error data'), 400);
        }


        /** @var pocketlistsCommentModel $item_model */
        $comment_model = pl2()->getModel(pocketlistsComment::class);
        $comment_in_db = $comment_model->getById($comment_ids);
        $list_id_available = pocketlistsRBAC::getAccessListForContact($this->getUser()->getId());

        /** validate */
        foreach ($comments as &$_comment) {
            /** set default */
            $comment_id = ifset($_comment, 'id', null);
            $_comment = [
                'id'                    => $comment_id,
                'item_id'               => null,
                'item_name'             => null,
                'contact_id'            => null,
                'comment'               => ifset($_comment, 'comment', null),
                'create_datetime'       => null,
                'client_touch_datetime' => null,
                'uuid'                  => null,
                'list_id'               => ifset($comment_in_db, $comment_id, 'list_id', null),
                'errors'                => [],
                'status_code'           => 'ok'
            ];

            if (empty($_comment['id'])) {
                $_comment['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'id');
            } elseif (!is_numeric($_comment['id'])) {
                $_comment['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'id');
            } elseif (!array_key_exists($_comment['id'], $comment_in_db)) {
                $_comment['errors'][] = _w('Comment not found');
            } elseif (empty($comment_in_db[$_comment['id']]['list_id']) || !in_array($comment_in_db[$_comment['id']]['list_id'], $list_id_available)) {
                $_comment['errors'][] = _w('Access denied');
            }

            if (!isset($_comment['comment'])) {
                $_comment['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'comment');
            } elseif (!is_string($_comment['comment'])) {
                $_comment['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'comment');
            }

            if (empty($_comment['errors'])) {
                $_comment = array_replace($comment_in_db[$_comment['id']], array_filter($_comment, function ($c) {return !is_null($c);}));
                $_comment += $comment_in_db[$_comment['id']];
                unset($_comment['errors']);
            } else {
                $_comment['status_code'] = 'error';
            }
        }

        $comments_ok = array_filter($comments, function ($c) {
            return $c['status_code'] === 'ok';
        });
        $comments_err = array_diff_key($comments, $comments_ok);
        if (!empty($comments_ok)) {
            try {
                foreach ($comments_ok as &$_comment_ok) {
                    $result = $comment_model->updateById($_comment_ok['id'], $_comment_ok);
                    if (!$result) {
                        $_comment_ok['status_code'] = 'error';
                        $_comment_ok['errors'][] = _w('Failed to update');
                    }
                }
                unset($_comment_ok);
                $this->saveLog(
                    pocketlistsLog::ENTITY_COMMENT,
                    pocketlistsLog::ACTION_UPDATE,
                    $comments_ok
                );
            } catch (Exception $ex) {
                throw new waAPIException('error', sprintf_wp('Error on transaction import save: %s', $ex->getMessage()), 400);
            }
        }

        $this->response = $this->filterFields(
            array_merge($comments_ok, $comments_err),
            [
                'id',
                'item_id',
                'item_name',
                'contact_id',
                'comment',
                'create_datetime',
                'client_touch_datetime',
                'uuid',
                'errors',
                'status_code'
            ], [
                'id' => 'int',
                'item_id' => 'int',
                'contact_id' => 'int',
                'create_datetime' => 'datetime',
                'client_touch_datetime' => 'datetime',
            ]
        );
    }
}
