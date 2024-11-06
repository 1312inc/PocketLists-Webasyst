<?php

class pocketlistsCommentsAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $comments = $this->readBodyAsJson();
        if (empty($comments)) {
            throw new pocketlistsApiException(_w('Missing `data`'), 400);
        } elseif (!is_array($comments)) {
            throw new pocketlistsApiException(_w('Type error `data`'), 400);
        }

        $items = [];
        $item_ids = array_unique(array_filter(array_column($comments, 'item_id')));
        $uuids = array_column($comments, 'uuid');

        /** @var pocketlistsCommentModel $model */
        $model = pl2()->getModel(pocketlistsComment::class);
        if (!empty($item_ids)) {
            $items = $model->query("
                SELECT id, list_id, name FROM pocketlists_item
                WHERE id IN (i:item_ids) AND key_list_id IS NULL
            ", ['item_ids' => $item_ids])->fetchAll('id');
        }
        if (!empty($uuids)) {
            $uuids = $this->getEntitiesByUuid('comment', $uuids);
            $uuids = array_keys($uuids);
        }
        /** validate */
        $user_id = $this->getUser()->getId();
        $list_access = pocketlistsRBAC::getAccessListForContact($user_id);
        foreach ($comments as &$_comment) {
            /** set default */
            $item_id = ifset($_comment, 'item_id', null);
            $_comment = [
                'id'                    => null,
                'item_id'               => $item_id,
                'item_name'             => ifset($items, $item_id, 'name', null),
                'contact_id'            => $user_id,
                'comment'               => ifset($_comment, 'comment', null),
                'create_datetime'       => date('Y-m-d H:i:s'),
                'client_touch_datetime' => ifset($_comment, 'client_touch_datetime', null),
                'uuid'                  => ifset($_comment, 'uuid', null),
                'list_id'               => ifset($items, $item_id, 'list_id', null),
                'success'               => true,
                'errors'                => []
            ];

            if (!isset($_comment['item_id'])) {
                $_comment['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'item_id');
            } elseif (!is_numeric($_comment['item_id'])) {
                $_comment['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'item_id');
            } elseif ($_comment['item_id'] < 1 || !array_key_exists($_comment['item_id'], $items)) {
                $_comment['errors'][] = _w('Item not found');
            } elseif (!in_array($_comment['list_id'], $list_access)) {
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
                    $dt = date_create($_comment['client_touch_datetime']);
                    if ($dt) {
                        $_comment['client_touch_datetime'] = $dt->format('Y-m-d H:i:s');
                    } else {
                        $_comment['errors'][] = _w('Unknown value client_touch_datetime');
                    }
                }
            }

            if (isset($_comment['uuid'])) {
                if (!is_string($_comment['uuid'])) {
                    $_comment['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'uuid');
                } elseif (in_array($_comment['uuid'], $uuids)) {
                    $_comment['errors'][] =  _w('Comment with UUID exists');
                }
            }

            if (!empty($_comment['errors'])) {
                $_comment['success'] = false;
            }
        }

        $comments_ok = array_filter($comments, function ($c) {
            return $c['success'];
        });
        $comments_err = array_diff_key($comments, $comments_ok);
        if (!empty($comments_ok)) {
            try {
                $comments_ok = array_values($comments_ok);
                $result = $model->multipleInsert($comments_ok);
                if ($result->getResult()) {
                    $last_id = $result->lastInsertId();
                    $rows_count = $result->affectedRows();
                    if ($rows_count === count($comments_ok)) {
                        foreach ($comments_ok as &$_comment) {
                            $_comment['id'] = $last_id++;
                        }
                        unset($_comment);
                        $this->saveLog(
                            pocketlistsLog::ENTITY_COMMENT,
                            pocketlistsLog::ACTION_ADD,
                            $comments_ok
                        );
                    } else {
                        throw new pocketlistsApiException(_w('Error on transaction'), 400);
                    }
                } else {
                    throw new pocketlistsApiException(_w('Error on transaction'), 400);
                }
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
                'client_touch_datetime',
                'uuid'
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
