<?php

class pocketlistsItemGetListMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $ids = $this->get('id');
        $list_id = $this->get('list_id');
        $starting_from = $this->get('starting_from');
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        $while = 'WHERE 1 = 1';
        if (isset($ids)) {
            if (!is_array($ids)) {
                throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'id'), 400);
            }
            $ids = array_unique(array_filter($ids, function ($_i) {
                return is_numeric($_i) && $_i > 0;
            }));
            $while .= ' AND i.id IN (i:item_ids)';
        }
        if (isset($list_id)) {
            if (!is_numeric($list_id)) {
                throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'list_id'), 400);
            } elseif ($list_id < 1) {
                throw new waAPIException('not_found', _w('List not found'), 404);
            }
            $while .= ' AND i.list_id = i:list_id';
        }
        if (isset($starting_from)) {
            if (!is_numeric($starting_from)) {
                throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'starting_from'), 400);
            } elseif ($starting_from < 1) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
            $starting_from = date('Y-m-d H:i:s', $starting_from);
            $while .= ' AND i.update_datetime >= s:starting_from';
        }
        if (isset($limit)) {
            if (!is_numeric($limit)) {
                throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'limit'), 400);
            } elseif ($limit < 1) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
            $limit = (int) min($limit, self::MAX_LIMIT);
        } else {
            $limit = self::DEFAULT_LIMIT;
        }
        if (isset($offset)) {
            if (!is_numeric($offset)) {
                throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'offset'), 400);
            } elseif ($offset < 0) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
            $offset = intval($offset);
        } else {
            $offset = 0;
        }

        $plim = pl2()->getModel(pocketlistsItem::class);
        $sql = $plim->getQuery(true);
        $items = $plim->query(
            "$sql $while ORDER BY i.parent_id, i.sort ASC, i.id DESC LIMIT i:offset, i:limit", [
            'item_ids'      => $ids,
            'list_id'       => (int) $list_id,
            'contact_id'    => $this->getUser()->getId(),
            'starting_from' => $starting_from,
            'limit'         => $limit,
            'offset'        => $offset
        ])->fetchAll('id');
        $total_count = (int) $plim->query('SELECT FOUND_ROWS()')->fetchField();
        $path = wa()->getDataUrl('attachments', true, pocketlistsHelper::APP_ID);
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
                'path'      => "$path/$item_id/$name"
            ];
        }

        $this->response = [
            'offset' => $offset,
            'limit'  => $limit,
            'count'  => $total_count,
            'data'   => $this->filterFields(
                $items,
                [
                    'id',
                    'list_id',
                    'contact_id',
                    'parent_id',
                    'sort',
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
                    'location_id',
                    'amount',
                    'currency_iso3',
                    'assigned_contact_id',
                    'repeat',
                    'key_list_id',
                    'favorite',
                    'attachments_count',
                    'comments_count',
                    'linked_entities_count',
                    'uuid',
                    'attachments'
                ],
                [
                    'id' => 'int',
                    'list_id' => 'int',
                    'contact_id' => 'int',
                    'parent_id' => 'int',
                    'sort' => 'int',
                    'has_children' => 'bool',
                    'status' => 'int',
                    'priority' => 'int',
                    'calc_priority' => 'int',
                    'create_datetime' => 'datetime',
                    'update_datetime' => 'datetime',
                    'complete_datetime' => 'datetime',
                    'complete_contact_id' => 'int',
                    'due_datetime' => 'datetime',
                    'location_id' => 'int',
                    'amount' => 'float',
                    'assigned_contact_id' => 'int',
                    'repeat' => 'int',
                    'key_list_id' => 'int',
                    'favorite' => 'bool',
                    'attachments_count' => 'int',
                    'comments_count' => 'int',
                    'linked_entities_count' => 'int'
                ]
            )
        ];
    }
}
