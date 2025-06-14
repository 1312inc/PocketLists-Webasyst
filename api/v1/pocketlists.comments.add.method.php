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
        $lists = [];
        $item_ids = array_unique(array_filter(array_column($comments, 'item_id')));
        $uuids = array_column($comments, 'uuid');

        $user_id = $this->getUser()->getId();
        $list_access = pocketlistsRBAC::getAccessListForContact($user_id);

        /** @var pocketlistsItemModel $model */
        $model = pl2()->getModel(pocketlistsItem::class);
        if (!empty($item_ids)) {
            $items = $model->select('id, list_id, name, contact_id')
                ->where('id IN (i:item_ids)', ['item_ids' => $item_ids])
                ->where('key_list_id IS NULL')
                ->fetchAll('id');

            $list_ids = array_unique(array_filter(array_column($items, 'list_id')));
            /** @var pocketlistsListModel $list_model */
            $list_model = pl2()->getModel(pocketlistsList::class);
            $lists = $list_model->select('id, private, archived')
                ->where('id IN (:list_ids)', ['list_ids' => array_intersect($list_access, $list_ids)])
                ->fetchAll('id');
        }
        if (!empty($uuids)) {
            $uuids = $this->getEntitiesByUuid('comment', $uuids);
            $uuids = array_keys($uuids);
        }
        /** validate */
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
                'update_datetime'       => null,
                'client_touch_datetime' => ifset($_comment, 'client_touch_datetime', null),
                'uuid'                  => ifset($_comment, 'uuid', null),
                'list_id'               => ifset($items, $item_id, 'list_id', null),
                'success'               => true,
                'errors'                => []
            ];

            if (!isset($_comment['item_id'])) {
                $_comment['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'item_id');
            } elseif (!is_numeric($_comment['item_id'])) {
                $_comment['errors'][] = sprintf_wp('Invalid data type: “%s”', 'item_id');
            } elseif ($_comment['item_id'] < 1 || !array_key_exists($_comment['item_id'], $items)) {
                $_comment['errors'][] = _w('Item not found');
            } elseif ($_comment['list_id'] && !in_array($_comment['list_id'], $list_access)) {
                $_comment['errors'][] = _w('Access denied');
            }

            if (isset($_comment['comment']) && !is_string($_comment['comment'])) {
                $_comment['errors'][] = sprintf_wp('Invalid data type: “%s”', 'comment');
            }

            if (isset($_comment['client_touch_datetime'])) {
                if (!is_string($_comment['client_touch_datetime'])) {
                    $_comment['errors'][] = sprintf_wp('Invalid data type: “%s”', 'client_touch_datetime');
                } else {
                    $dt = date_create($_comment['client_touch_datetime']);
                    if ($dt) {
                        $_comment['client_touch_datetime'] = $dt->format('Y-m-d H:i:s');
                    } else {
                        $_comment['errors'][] = _w('Invalid value client_touch_datetime');
                    }
                }
            }

            if (isset($_comment['uuid'])) {
                if (!is_string($_comment['uuid'])) {
                    $_comment['errors'][] = sprintf_wp('Invalid data type: “%s”', 'uuid');
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
                $result = pl2()->getModel(pocketlistsComment::class)->multipleInsert($comments_ok);
                if ($result->getResult()) {
                    $last_id = $result->lastInsertId();
                    $rows_count = $result->affectedRows();
                    if ($rows_count === count($comments_ok)) {
                        foreach ($comments_ok as &$_comment) {
                            $_comment['id'] = $last_id++;
                            if (ifempty($lists, $_comment['list_id'], 'private', 0) == 0) {
                                $this->systemLogAction(
                                    pocketlistsLogAction::ITEM_COMMENT,
                                    [
                                        'list_id'    => $_comment['list_id'],
                                        'comment_id' => $_comment['id'],
                                        'item_id'    => $_comment['item_id'],
                                    ]
                                );
                            }
                        }
                        unset($_comment);

                        $model->updateById(
                            array_filter(array_unique(array_column($comments_ok, 'item_id'))),
                            ['activity_datetime' => date('Y-m-d H:i:s')]
                        );

                        $no_private_comments = array_filter($comments_ok, function ($c) use ($lists) {
                            return ifempty($lists, $c['list_id'], 'private', 0) == 0;
                        });
                        if ($no_private_comments) {
                            (new pocketlistsNotificationAboutNewComment())->multiplicityNotify($no_private_comments);
                        }

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
                'update_datetime',
                'client_touch_datetime',
                'uuid'
            ], [
                'id' => 'int',
                'item_id' => 'int',
                'contact_id' => 'int',
                'create_datetime' => 'datetime',
                'update_datetime' => 'datetime',
                'client_touch_datetime' => 'dateiso',
            ]
        );
    }
}
