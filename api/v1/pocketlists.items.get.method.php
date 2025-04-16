<?php

class pocketlistsItemsGetMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $ids = $this->get('id');
        $list_id = $this->get('list_id');
        $contact_id = $this->get('contact_id');
        $assigned_contact_id = $this->get('assigned_contact_id');
        $complete_contact_id = $this->get('complete_contact_id');
        $due_date = $this->get('due_date');
        $location_id = $this->get('location_id');
        $status = $this->get('status');
        $tag = $this->get('tag');
        $external_app_id = $this->get('external_app_id');
        $external_entity_type = $this->get('external_entity_type');
        $external_entity_id = $this->get('external_entity_id');
        $starting_from = $this->get('starting_from');
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        if (isset($ids)) {
            if (!is_array($ids)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'id'), 400);
            }
            $ids = array_unique(array_filter($ids, function ($_i) {
                return is_numeric($_i) && $_i > 0;
            }));
            if (empty($ids)) {
                throw new pocketlistsApiException(_w('Items not found'), 404);
            }
        }
        if (isset($list_id)) {
            if (!is_numeric($list_id)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'list_id'), 400);
            } elseif ($list_id < 1) {
                throw new pocketlistsApiException(_w('List not found'), 404);
            }
        }
        if (isset($contact_id)) {
            if (!is_numeric($contact_id)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'contact_id'), 400);
            } elseif ($contact_id < 1) {
                throw new pocketlistsApiException(_w('Contact not found'), 404);
            }
        }
        if (isset($assigned_contact_id)) {
            if (!is_numeric($assigned_contact_id)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'assigned_contact_id'), 400);
            } elseif ($assigned_contact_id < 1) {
                throw new pocketlistsApiException(_w('Assigned contact not found'), 404);
            }
        }
        if (isset($complete_contact_id)) {
            if (!is_numeric($complete_contact_id)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'complete_contact_id'), 400);
            } elseif ($complete_contact_id < 1) {
                throw new pocketlistsApiException(_w('Complete contact not found'), 404);
            }
        }
        if (isset($due_date)) {
            if (!is_string($due_date)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'due_date'), 400);
            } else {
                $dt = date_create($due_date);
                if ($dt) {
                    $due_date = $dt->format('Y-m-d');
                } else {
                    throw new pocketlistsApiException(_w('Unknown value due_date'), 400);
                }
            }
        }
        if (isset($location_id)) {
            if (!is_numeric($location_id)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'location_id'), 400);
            } elseif ($location_id < 1) {
                throw new pocketlistsApiException(_w('Location not found'), 404);
            }
        }
        if (isset($status)) {
            if (!is_numeric($status)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'status'), 400);
            } elseif (!in_array($status, [pocketlistsItem::STATUS_UNDONE, pocketlistsItem::STATUS_DONE])) {
                throw new pocketlistsApiException(_w('Unknown value status'), 400);
            }
            $status = (int) $status;
        }
        if (isset($tag) && !is_string($tag)) {
            throw new pocketlistsApiException(_w('Invalid tag'), 400);
        }
        if (isset($external_app_id) && !is_string($external_app_id)) {
            throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'external_app_id'), 400);
        }
        if (isset($external_entity_type)) {
            if (!is_string($external_entity_type)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'external_entity_type'), 400);
            } elseif (!isset($external_app_id)) {
                throw new pocketlistsApiException(sprintf_wp('Missing required parameter: “%s”.', 'external_app_id'), 400);
            }
        }
        if (isset($external_entity_id)) {
            if (!is_string($external_entity_id)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'external_entity_id'), 400);
            } elseif (!isset($external_app_id)) {
                throw new pocketlistsApiException(sprintf_wp('Missing required parameter: “%s”.', 'external_app_id'), 400);
            } elseif (!isset($external_entity_type)) {
                throw new pocketlistsApiException(sprintf_wp('Missing required parameter: “%s”.', 'external_entity_type'), 400);
            }
        }
        if (isset($starting_from)) {
            if (!is_string($starting_from)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'starting_from'), 400);
            } else {
                $dt = date_create($starting_from, new DateTimeZone('UTC'));
                if ($dt) {
                    $dt->setTimezone(new DateTimeZone(date_default_timezone_get()));
                    $starting_from = $dt->format('Y-m-d H:i:s');
                } else {
                    throw new pocketlistsApiException(_w('Unknown value starting_from'), 400);
                }
            }
        }
        if (isset($limit)) {
            if (!is_numeric($limit)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'limit'), 400);
            } elseif ($limit < 1) {
                throw new pocketlistsApiException(_w('The parameter has a negative value'), 400);
            }
            $limit = (int) min($limit, self::MAX_LIMIT);
        } else {
            $limit = self::DEFAULT_LIMIT;
        }
        if (isset($offset)) {
            if (!is_numeric($offset)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'offset'), 400);
            } elseif ($offset < 0) {
                throw new pocketlistsApiException(_w('The parameter has a negative value'), 400);
            }
            $offset = intval($offset);
        } else {
            $offset = 0;
        }

        $list_ids = pocketlistsRBAC::getAccessListForContact(pl2()->getUser()->getId());
        if (isset($list_id)) {
            if (in_array($list_id, $list_ids)) {
                $list_ids = [$list_id];
            } else {
                throw new pocketlistsApiException(_w('List access denied'), 403);
            }
        } elseif (empty($list_ids)) {
            $list_ids = [null];
        }

        $item_move_ids = [];
        $current_user_id = $this->getUser()->getId();
        $item_model = pl2()->getModel(pocketlistsItem::class);
        $sql_parts = $item_model->getQueryComponents(true);
        $sql_parts['where']['and'][] = 'i.key_list_id IS NULL';
        if ($ids) {
            $sql_parts['where']['and'][] = 'i.id IN (i:item_ids)';
        }
        if (isset($list_id)) {
            $sql_parts['where']['and'][] = 'i.list_id IN (i:list_ids)';
        } else {
            $opt = [
                'i.contact_id = i:current_user_id',
                'i.assigned_contact_id = i:current_user_id'
            ];
            if (!pocketlistsRBAC::canAssign()) {
                $opt[] = 'i.complete_contact_id = i:current_user_id';
            } elseif (isset($complete_contact_id)) {
                $opt[] = 'i.complete_contact_id = i:complete_contact_id';
            }
            $sql_parts['where']['and'][] = 'i.list_id IN (i:list_ids) OR (i.list_id IS NULL AND ('.implode(' OR ', $opt).'))';
        }
        if (isset($contact_id)) {
            $sql_parts['where']['and'][] = 'i.contact_id = i:contact_id';
        } else {
            $contact_id = $current_user_id;
        }
        if (isset($assigned_contact_id)) {
            $sql_parts['where']['and'][] = 'i.assigned_contact_id = i:assigned_contact_id';
        }
        if (isset($complete_contact_id)) {
            $sql_parts['where']['and'][] = 'i.complete_contact_id = i:complete_contact_id';
        }
        if (isset($due_date)) {
            $sql_parts['where']['and'][] = 'i.due_date = s:due_date';
        }
        if ($location_id || $external_app_id) {
            if ($location_id) {
                $sql_parts['join']['pl'] = 'LEFT JOIN pocketlists_location pl ON pl.id = i.location_id';
                $sql_parts['where']['and'][] = 'i.location_id = i:location_id';
            }
            if ($external_app_id) {
                $sql_parts['join']['pil2'] = 'LEFT JOIN pocketlists_item_link pil2 ON pil2.item_id = i.id';
                $sql_parts['where']['and'][] = 'pil2.app = s:app_id';
                if ($external_entity_type) {
                    $sql_parts['where']['and'][] = 'pil2.entity_type = s:entity_type';
                }
                if ($external_entity_id) {
                    $sql_parts['where']['and'][] = 'pil2.entity_id = s:entity_id';
                }
            }
        }
        if (isset($status)) {
            $sql_parts['where']['and'][] = 'i.status = i:status';
        }
        if ($tag) {
            $sql_parts['join']['pit'] = 'LEFT JOIN pocketlists_item_tags pit ON pit.item_id = i.id';
            $sql_parts['join']['pt'] = 'LEFT JOIN pocketlists_tag pt ON pt.id = pit.tag_id';
            $sql_parts['where']['and'][] = 'pt.`text` = s:text';
        }
        if ($starting_from) {
            if (isset($list_id) || $status == pocketlistsItem::STATUS_DONE) {
                $item_move_ids = $this->getMoveItemIds($list_id, $status, $starting_from);
                if ($item_move_ids) {
                    $sql_parts['where']['or'][] = 'i.id IN (i:item_move_ids)';
                }
            }
            $sql_parts['where']['and'][] = 'i.update_datetime >= s:starting_from OR i.create_datetime >= s:starting_from OR i.activity_datetime >= s:starting_from';
            $sql_parts['order by'] = ['i.update_datetime DESC'];
        } elseif ($status === 1) {
            $sql_parts['order by'] = ['i.complete_datetime DESC'];
        } else {
            $sql_parts['order by'] = ['i.sort, i.rank, i.id DESC'];
        }

        $sql = $item_model->buildSqlComponents($sql_parts);
        $items = $item_model->query(
            "$sql LIMIT i:offset, i:limit", [
            'item_ids'            => $ids,
            'item_move_ids'       => $item_move_ids,
            'list_ids'            => $list_ids,
            'location_id'         => $location_id,
            'status'              => $status,
            'text'                => $tag,
            'app_id'              => $external_app_id,
            'entity_type'         => $external_entity_type,
            'entity_id'           => $external_entity_id,
            'current_user_id'     => $current_user_id,
            'contact_id'          => $contact_id,
            'assigned_contact_id' => $assigned_contact_id,
            'complete_contact_id' => $complete_contact_id,
            'due_date'            => $due_date,
            'starting_from'       => $starting_from,
            'limit'               => $limit,
            'offset'              => $offset
        ])->fetchAll('id');
        $total_count = (int) $item_model->query('SELECT FOUND_ROWS()')->fetchField();

        $ids = array_keys($items);
        if ($ids) {
            $attachments = [];
            $attachments_in_db = pl2()->getModel(pocketlistsAttachment::class)->getByField('item_id', $ids, true);
            foreach ($attachments_in_db as $_attachment) {
                $_attachment['file_name'] = $_attachment['filename'];
                if (!isset($attachments[$_attachment['item_id']])) {
                    $attachments[$_attachment['item_id']] = [];
                }
                $attachments[$_attachment['item_id']][] = pocketlistsAttachment::setUrl($_attachment);
            }
            unset($attachments_in_db, $_attachment);

            $links = $this->getLinks($ids);

            $tags = [];
            $tags_in_db = pl2()->getModel(pocketlistsItemTags::class)->getTags($ids);
            foreach ($tags_in_db as $_tag) {
                if (!isset($tags[$_tag['item_id']])) {
                    $tags[$_tag['item_id']] = [];
                }
                $tags[$_tag['item_id']][] = $_tag['text'];
            }
            unset($tags_in_db, $_tag);
        }

        foreach ($items as &$_item) {
            $_item += [
                'attachments'    => [],
                'external_links' => [],
                'tags'           => []
            ];
            $_item['extended_data'] = [
                'comments_count' => (int) $_item['comments_count']
            ];

            if (isset($attachments[$_item['id']])) {
                $_item['attachments'] = $this->filterFields(
                    $attachments[$_item['id']],
                    ['id', 'item_id', 'file_name', 'ext', 'size', 'upload_datetime', 'uuid', 'download_url', 'preview_url'],
                    ['id' => 'int', 'size' => 'int', 'item_id' => 'int', 'upload_datetime' => 'datetime']
                );
            }
            if (isset($links[$_item['id']])) {
                $_item['external_links'] = $links[$_item['id']];
            }
            if (isset($tags[$_item['id']])) {
                $_item['tags'] = $tags[$_item['id']];
            }
        }
        unset($_item);

        $this->response['meta'] = [
            'offset' => $offset,
            'limit'  => $limit,
            'count'  => $total_count
        ];
        $this->response['data'] = $this->responseListWrapper(
            $items,
            [
                'id',
                'list_id',
                'contact_id',
                'parent_id',
                'sort',
                'rank',
                'has_children',
                'status',
                'priority',
                'calc_priority',
                'create_datetime',
                'update_datetime',
                'activity_datetime',
                'complete_datetime',
                'complete_contact_id',
                'name',
                'note',
                'due_date',
                'due_datetime',
                'client_touch_datetime',
                'location_id',
                'amount',
                'currency_iso3',
                'assigned_contact_id',
                'repeat_frequency',
                'repeat_interval',
                'repeat_occurrence',
                'favorite',
                'key_list_id',
                'uuid',
                'attachments',
                'external_links',
                'tags',
                'extended_data'
            ], [
                'id' => 'int',
                'list_id' => 'int',
                'contact_id' => 'int',
                'parent_id' => 'int',
                'sort' => 'int',
                'has_children' => 'int',
                'status' => 'int',
                'priority' => 'int',
                'calc_priority' => 'int',
                'create_datetime' => 'datetime',
                'update_datetime' => 'datetime',
                'activity_datetime' => 'datetime',
                'complete_datetime' => 'datetime',
                'complete_contact_id' => 'int',
                'due_datetime' => 'datetime',
                'client_touch_datetime' => 'dateiso',
                'location_id' => 'int',
                'amount' => 'float',
                'assigned_contact_id' => 'int',
                'repeat_frequency' => 'int',
                'repeat_occurrence' => 'int',
                'favorite' => 'int',
                'key_list_id' => 'int'
            ]
        );
    }

    /**
     * @param $list_id
     * @param $status
     * @param $starting_from
     * @return array
     * @throws waDbException
     * @throws waException
     */
    private function getMoveItemIds($list_id, $status, $starting_from)
    {
        $where_part = [];
        if ($list_id) {
            $where_part[] = 'prev_list_id IN (i:list_id)';
        }
        if ($status == pocketlistsItem::STATUS_DONE) {
            $where_part[] = 'prev_status = 0';
        }
        if (empty($where_part)) {
            return [];
        }
        $i_move = pl2()->getModel(pocketlistsItemMove::class)->query(sprintf("
            SELECT item_id FROM pocketlists_item_move
            WHERE (%s) AND `datetime` >= s:starting_from
            GROUP by item_id, prev_list_id
        ", implode(' OR ', $where_part)), [
            'list_id' => $list_id,
            'starting_from' => $starting_from
        ])->fetchAll();

        return array_column($i_move, 'item_id');
    }
}
