<?php

class pocketlistsCommentsGetMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $item_id = $this->get('item_id');
        $starting_from = $this->get('starting_from');
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        $where = 'WHERE 1 = 1';
        if (isset($item_id)) {
            if (!is_numeric($item_id)) {
                throw new pocketlistsApiException(_w('Unknown value'), 400);
            } elseif ($item_id < 1) {
                throw new pocketlistsApiException(_w('Item not found'), 404);
            }
            $where .= ' AND c.item_id = i:item_id';
        }
        if (isset($starting_from)) {
            if (!is_string($starting_from)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'starting_from'), 400);
            }
            $dt = date_create($starting_from, new DateTimeZone('UTC'));
            if ($dt) {
                $dt->setTimezone(new DateTimeZone(date_default_timezone_get()));
                $starting_from = $dt->format('Y-m-d H:i:s');
            } else {
                throw new pocketlistsApiException(_w('Unknown value starting_from'), 400);
            }
            $where .= ' AND c.update_datetime >= s:starting_from OR c.create_datetime >= s:starting_from';
        }
        if (isset($limit)) {
            if (!is_numeric($limit)) {
                throw new pocketlistsApiException(_w('Unknown value'), 400);
            } elseif ($limit < 1) {
                throw new pocketlistsApiException(_w('The parameter has a negative value'), 400);
            }
            $limit = (int) min($limit, self::MAX_LIMIT);
        } else {
            $limit = self::DEFAULT_LIMIT;
        }
        if (isset($offset)) {
            if (!is_numeric($offset)) {
                throw new pocketlistsApiException(_w('Unknown value'), 400);
            } elseif ($offset < 0) {
                throw new pocketlistsApiException(_w('The parameter has a negative value'), 400);
            }
            $offset = intval($offset);
        } else {
            $offset = 0;
        }

        $plcm = pl2()->getModel(pocketlistsComment::class);
        $sql = $plcm->getSql(true);
        $comments = $plcm->query(
            "$sql $where ORDER BY c.update_datetime DESC, c.id DESC LIMIT i:offset, i:limit", [
            'item_id'       => (int) $item_id,
            'starting_from' => $starting_from,
            'limit'         => $limit,
            'offset'        => $offset
        ]
        )->fetchAll();
        $total_count = (int) $plcm->query('SELECT FOUND_ROWS()')->fetchField();
        $this->response['meta'] = [
            'offset' => $offset,
            'limit'  => $limit,
            'count'  => $total_count
        ];
        $this->response['data'] = $this->responseListWrapper(
            $comments,
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
                'list_id' => 'int',
                'contact_id' => 'int',
                'create_datetime' => 'datetime',
                'update_datetime' => 'datetime',
                'client_touch_datetime' => 'datetime'
            ]
        );
    }
}
