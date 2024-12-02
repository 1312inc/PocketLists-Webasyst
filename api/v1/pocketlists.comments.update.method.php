<?php

class pocketlistsCommentsUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PATCH;

    public function execute()
    {
        $comments = $this->readBodyAsJson();
        if (empty($comments)) {
            throw new pocketlistsApiException(_w('Missing `data`'), 400);
        } elseif (!is_array($comments)) {
            throw new pocketlistsApiException(_w('Type error `data`'), 400);
        }

        $comment_ids = array_unique(array_column($comments, 'id'));
        if (empty($comment_ids)) {
            throw new pocketlistsApiException(_w('Missing `data`'), 400);
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
                'action'                => (ifset($_comment, 'action', null) === self::ACTIONS[1] ? self::ACTIONS[1] : self::ACTIONS[0]),
                'id'                    => $comment_id,
                'item_id'               => null,
                'item_name'             => null,
                'contact_id'            => null,
                'comment'               => ifset($_comment, 'comment', null),
                'create_datetime'       => null,
                'update_datetime'       => date('Y-m-d H:i:s'),
                'client_touch_datetime' => ifset($_comment, 'client_touch_datetime', null),
                'uuid'                  => null,
                'list_id'               => ifset($comment_in_db, $comment_id, 'list_id', null),
                'success'               => true,
                'errors'                => []
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

            if (isset($_comment['client_touch_datetime'])) {
                if (!is_string($_comment['client_touch_datetime'])) {
                    $_comment['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'client_touch_datetime');
                } else {
                    $dt = date_create($_comment['client_touch_datetime'], new DateTimeZone('UTC'));
                    if ($dt) {
                        $_comment['client_touch_datetime'] = $dt->format('Y-m-d H:i:s');
                    } else {
                        $_comment['errors'][] = _w('Unknown value client_touch_datetime');
                    }
                }
            }

            if (empty($_comment['errors'])) {
                if ($_comment['action'] == self::ACTIONS[0]) {
                    // patch
                    $_comment = array_replace($comment_in_db[$_comment['id']], array_filter($_comment, function ($c) {return !is_null($c);}));
                } else {
                    // update
                    $_comment += $comment_in_db[$_comment['id']];
                }
                unset($_comment['errors']);
            } else {
                $_comment['success'] = false;
            }
        }

        $comments_ok = array_filter($comments, function ($c) {
            return $c['success'];
        });
        $comments_err = array_diff_key($comments, $comments_ok);
        if (!empty($comments_ok)) {
            try {
                foreach ($comments_ok as &$_comment_ok) {
                    $result = $comment_model->updateById($_comment_ok['id'], $_comment_ok);
                    if (!$result) {
                        $_comment_ok['success'] = false;
                        $_comment_ok['errors'][] = _w('Failed to update');
                    }
                }
                unset($_comment_ok);
                $this->saveLog(
                    pocketlistsLog::ENTITY_COMMENT,
                    pocketlistsLog::ACTION_UPDATE,
                    array_filter($comments_ok, function ($c) {
                        return $c['success'];
                    })
                );
            } catch (Exception $ex) {
                throw new pocketlistsApiException(sprintf_wp('Error on transaction import save: %s', $ex->getMessage()), 400);
            }
        }

        $this->response['data'] = $this->responseWrapper(
            array_merge($comments_ok, $comments_err),
            [
                'id',
                'item_id',
                'item_name',
                'contact_id',
                'comment',
                'create_datetime',
                'update_datetime',
                'client_touch_datetime',
                'uuid'
            ], [
                'id' => 'int',
                'item_id' => 'int',
                'contact_id' => 'int',
                'create_datetime' => 'datetime',
                'update_datetime' => 'datetime',
                'client_touch_datetime' => 'datetime',
            ]
        );
    }
}
