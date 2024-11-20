<?php

class pocketlistsItemsGetMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $ids = $this->get('id');
        $list_id = $this->get('list_id');
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
            }
            $status = (int) $status;
        } else {
            $status = 0;
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
                $dt = date_create($starting_from);
                if ($dt) {
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
        }
        $item_model = pl2()->getModel(pocketlistsItem::class);
        $sql_parts = $item_model->getQueryComponents(true);
        $sql_parts['where']['and'][] = 'i.status = i:status';
        $sql_parts['order by'] = ['i.parent_id, i.sort, i.rank ASC', 'i.id DESC'];

        if ($ids) {
            $sql_parts['where']['and'][] = 'i.id IN (i:item_ids)';
        }
        if ($location_id || $external_app_id) {
            $sql_parts['where']['and'][] = 'i.list_id IN (i:list_ids) OR i.list_id IS NULL';
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
                    $sql_parts['where']['and'][] = 'pil2.entity_id = i:entity_id';
                }
            }
        } else {
            $sql_parts['where']['and'][] = 'i.list_id IN (i:list_ids)';
        }
        if ($tag) {
            $sql_parts['join']['pit'] = 'LEFT JOIN pocketlists_item_tags pit ON pit.item_id = i.id';
            $sql_parts['join']['pt'] = 'LEFT JOIN pocketlists_tag pt ON pt.id = pit.tag_id';
            $sql_parts['where']['and'][] = 'pt.`text` = s:text';
        }
        if ($starting_from) {
            $sql_parts['where']['and'][] = 'i.update_datetime >= s:starting_from';
        }

        $sql = $item_model->buildSqlComponents($sql_parts);
        $items = $item_model->query(
            "$sql LIMIT i:offset, i:limit", [
            'item_ids'      => $ids,
            'list_ids'      => $list_ids,
            'location_id'   => $location_id,
            'status'        => $status,
            'text'          => $tag,
            'app_id'        => $external_app_id,
            'entity_type'   => $external_entity_type,
            'entity_id'     => $external_entity_id,
            'contact_id'    => $this->getUser()->getId(),
            'starting_from' => $starting_from,
            'limit'         => $limit,
            'offset'        => $offset
        ])->fetchAll('id');
        $total_count = (int) $item_model->query('SELECT FOUND_ROWS()')->fetchField();

        $ids = array_keys($items);
        if ($ids) {
            $tags = [];
            $result = pl2()->getModel(pocketlistsItemTags::class)->getTags($ids);
            foreach ($result as $_res) {
                if (!isset($tags[$_res['item_id']])) {
                    $tags[$_res['item_id']] = [];
                }
                $tags[$_res['item_id']][] = $_res['text'];
            }
        }
        foreach ($items as &$_item) {
            $_item['extended_data'] = [
                'comments_count' => (int) $_item['comments_count']
            ];
            if (isset($tags[$_item['id']])) {
                $_item['tags'] = $tags[$_item['id']];
            }
        }
        unset($_item);

        $attachments = pl2()->getEntityFactory(pocketlistsAttachment::class)->findByFields(
            'item_id',
            array_keys($items),
            true
        );

        /** @var pocketlistsAttachment $_attachment */
        foreach ($attachments as $_attachment) {
            $name = $_attachment->getFilename();
            $item_id = $_attachment->getItemId();
            if (!isset($items[$item_id]['attachments'])) {
                $items[$item_id]['attachments'] = [];
            }
            $items[$item_id]['attachments'][] = [
                'id'        => $_attachment->getId(),
                'item_id'   => $item_id,
                'file_name' => $name,
                'file_type' => $_attachment->getFiletype(),
                'url'       => wa()->getDataUrl("attachments/$item_id/", true, pocketlistsHelper::APP_ID, true).$name,
                'uuid'      => $_attachment->getUuid()
            ];
        }

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
                'repeat',
                'key_list_id',
                'uuid',
                'tags',
                'attachments',
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
                'complete_datetime' => 'datetime',
                'complete_contact_id' => 'int',
                'due_datetime' => 'datetime',
                'client_touch_datetime' => 'datetime',
                'location_id' => 'int',
                'amount' => 'float',
                'assigned_contact_id' => 'int',
                'repeat' => 'int',
                'key_list_id' => 'int',
                'comments_count' => 'int'
            ]
        );
    }
}
