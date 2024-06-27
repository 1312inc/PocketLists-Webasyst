<?php

class pocketlistsItemGetMethod extends pocketlistsApiAbstractMethod
{
    const MAX_LIMIT = 500;
    const DEFAULT_LIMIT = 30;

    protected $method = self::METHOD_GET;

    public function execute()
    {
        $while = 'WHERE 1 = 1';
        $id = $this->get('id');
        $list_id = $this->get('list_id');
        $starting_from = $this->get('starting_from');
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        if (isset($id)) {
            if (!is_numeric($id)) {
                throw new waAPIException('unknown_value', _w('Unknown value'), 400);
            } elseif ($id < 1) {
                throw new waAPIException('not_found', _w('Item not found'), 404);
            }
            $while .= ' AND i.id = i:item_id';
        }
        if (isset($list_id)) {
            if (!is_numeric($list_id)) {
                throw new waAPIException('unknown_value', _w('Unknown value'), 400);
            } elseif ($list_id < 1) {
                throw new waAPIException('not_found', _w('List not found'), 404);
            }
            $while .= ' AND i.list_id = i:list_id';
        }
        if (isset($starting_from)) {
            if (!is_numeric($starting_from)) {
                throw new waAPIException('unknown_value', _w('Unknown value'), 400);
            } elseif ($starting_from < 1) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
            $starting_from = date('Y-m-d H:i:s', $starting_from);
            $while .= ' AND i.update_datetime >= s:starting_from';
        }
        if (isset($limit)) {
            if (!is_numeric($limit)) {
                throw new waAPIException('unknown_value', _w('Unknown value'), 400);
            } elseif ($limit < 1) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
            $limit = min($limit, self::MAX_LIMIT);
        } else {
            $limit = self::DEFAULT_LIMIT;
        }
        if (isset($offset)) {
            if (!is_numeric($offset)) {
                throw new waAPIException('unknown_value', _w('Unknown value'), 400);
            } elseif ($offset < 1) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
        } else {
            $offset = 0;
        }

        $plim = pl2()->getModel(pocketlistsItem::class);
        $sql = $plim->getQuery();
        $items = $plim->query(
            "$sql $while ORDER BY i.parent_id, i.sort ASC, i.id DESC LIMIT i:offset, i:limit", [
            'item_id'       => (int) $id,
            'list_id'       => (int) $list_id,
            'contact_id'    => $this->getUser()->getId(),
            'starting_from' => $starting_from,
            'limit'         => (int) $limit,
            'offset'        => (int) $offset
        ])->fetchAll();

        $this->response = $this->filterFields(
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
                'linked_entities_count'
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
        );
    }
}
