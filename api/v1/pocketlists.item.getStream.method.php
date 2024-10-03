<?php

class pocketlistsItemGetStreamMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $filter = $this->get('filter', true);
        $limit  = $this->get('limit');
        $offset = $this->get('offset');

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

        if (!is_string($filter)) {
            throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'filter'), 400);
        }

        $filter = trim(trim($filter), '/');
        if ($filter === '') {
            throw new waAPIException('required_param', sprintf_wp('Missing required parameter: “%s”.', 'filter'), 400);
        }

        list($items, $total_count) = $this->getItems($filter, $limit, $offset);

        $this->response = [
            'filter' => "/$filter",
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
                    'location_id',
                    'amount',
                    'currency_iso3',
                    'assigned_contact_id',
                    'repeat',
                    'key_list_id',
                    'uuid',
                    'attachments'
                ],
                [
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
                    'location_id' => 'int',
                    'amount' => 'float',
                    'assigned_contact_id' => 'int',
                    'repeat' => 'int',
                    'key_list_id' => 'int'
                ]
            )
        ];
    }

    private function getItems($filter, $limit, $offset)
    {
        $available_filters = [
            'upnext',
            'due',
            'priority',
            'user',
            'search'
        ];
        $filter_split = explode('/', $filter);
        if (
            !in_array(count($filter_split), [1, 2])
            || !in_array($filter_split[0], $available_filters)
        ) {
            throw new waAPIException('unknown_value', _w('Unknown filter value'), 400);
        }

        $available_list_ids = pocketlistsRBAC::getAccessListForContact(pl2()->getUser()->getId());
        if (empty($available_list_ids)) {
            return [[], 0];
        }

        $where = 'i.list_id IN (:list_ids)';
        $sort  = '1 = 1';
        $plim  = pl2()->getModel(pocketlistsItem::class);
        switch ($filter_split[0]) {
            case 'upnext':
                /** /upnext */
                if (!empty($filter_split[1])) {
                    throw new waAPIException('unknown_value', _w('Unknown filter value'), 400);
                }
                $where .= ' AND (i.due_date IS NOT NULL OR i.due_datetime IS NOT NULL)';
                $sort = 'i.calc_priority DESC, i.due_date ASC';
                break;
            case 'due':
                /** /due */
                if (!empty($filter_split[1])) {
                    throw new waAPIException('unknown_value', _w('Unknown filter value'), 400);
                }
                $where .= ' AND (i.due_date IS NOT NULL OR i.due_datetime IS NOT NULL)';
                $sort = 'i.due_date ASC';
                break;
            case 'priority':
                /** /priority */
                if (!empty($filter_split[1])) {
                    throw new waAPIException('unknown_value', _w('Unknown filter value'), 400);
                }
                $sort = 'i.priority DESC';
                break;
            case 'user':
                /** /user/ID */
                if (
                    !isset($filter_split[1])
                    || !is_numeric($filter_split[1])
                    || $filter_split[1] < 1
                    || $filter_split[1] != (int) $filter_split[1]
                ) {
                    throw new waAPIException('unknown_value', _w('Unknown user'), 400);
                }
                $where .= ' AND assigned_contact_id = '.(int) $filter_split[1];
                break;
            case 'search':
                /** /search/KEYWORD */
                if (!isset($filter_split[1]) || !empty($filter_split[2])) {
                    throw new waAPIException('empty_value', _w('Empty value'), 400);
                }
                $where .= " AND i.name LIKE '%".$plim->escape($filter_split[1])."%'";
                break;
        }

        $sql = $plim->getQuery(true);
        $items = $plim->query(
            "$sql WHERE $where ORDER BY $sort LIMIT i:offset, i:limit", [
            'list_ids'   => $available_list_ids,
            'contact_id' => $this->getUser()->getId(),
            'limit'      => $limit,
            'offset'     => $offset
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

        return [$items, $total_count];
    }
}
