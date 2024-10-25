<?php

class pocketlistsCommentsGetMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $item_id = $this->get('item_id');
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        $where = 'WHERE 1 = 1';
        if (isset($item_id)) {
            if (!is_numeric($item_id)) {
                throw new waAPIException('unknown_value', _w('Unknown value'), 400);
            } elseif ($item_id < 1) {
                throw new waAPIException('not_found', _w('Item not found'), 404);
            }
            $where .= ' AND c.item_id = i:item_id';
        }
        if (isset($limit)) {
            if (!is_numeric($limit)) {
                throw new waAPIException('unknown_value', _w('Unknown value'), 400);
            } elseif ($limit < 1) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
            $limit = (int) min($limit, self::MAX_LIMIT);
        } else {
            $limit = self::DEFAULT_LIMIT;
        }
        if (isset($offset)) {
            if (!is_numeric($offset)) {
                throw new waAPIException('unknown_value', _w('Unknown value'), 400);
            } elseif ($offset < 0) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
            $offset = intval($offset);
        } else {
            $offset = 0;
        }

        $plcm = pl2()->getModel(pocketlistsComment::class);
        $sql = $plcm->getSql(true);
        $comments = $plcm->query(
            "$sql $where ORDER BY c.create_datetime LIMIT i:offset, i:limit", [
            'item_id' => (int) $item_id,
            'limit'   => $limit,
            'offset'  => $offset
        ]
        )->fetchAll();
        $total_count = (int) $plcm->query('SELECT FOUND_ROWS()')->fetchField();

        $this->response = [
            'offset' => $offset,
            'limit'  => $limit,
            'count'  => $total_count,
            'data'   => $this->filterFields(
                $comments,
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
                    'list_id' => 'int',
                    'contact_id' => 'int',
                    'create_datetime' => 'datetime',
                    'client_touch_datetime' => 'datetime'
                ]
            )
        ];
    }
}
