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
                $this->http_status_code = 400;
                $this->response = [
                    'status_code' => 'error',
                    'error'       => _w('Unknown value'),
                    'data'        => []
                ];
                return;
            } elseif ($item_id < 1) {
                $this->http_status_code = 400;
                $this->response = [
                    'status_code' => 'error',
                    'error'       => _w('Item not found'),
                    'data'        => []
                ];
                return;
            }
            $where .= ' AND c.item_id = i:item_id';
        }
        if (isset($limit)) {
            if (!is_numeric($limit)) {
                $this->http_status_code = 400;
                $this->response = [
                    'status_code' => 'error',
                    'error'       => _w('Unknown value'),
                    'data'        => []
                ];
                return;
            } elseif ($limit < 1) {
                $this->http_status_code = 400;
                $this->response = [
                    'status_code' => 'error',
                    'error'       => _w('The parameter has a negative value'),
                    'data'        => []
                ];
                return;
            }
            $limit = (int) min($limit, self::MAX_LIMIT);
        } else {
            $limit = self::DEFAULT_LIMIT;
        }
        if (isset($offset)) {
            if (!is_numeric($offset)) {
                $this->http_status_code = 400;
                $this->response = [
                    'status_code' => 'error',
                    'error'       => _w('Unknown value'),
                    'data'        => []
                ];
                return;
            } elseif ($offset < 0) {
                $this->http_status_code = 400;
                $this->response = [
                    'status_code' => 'error',
                    'error'       => _w('The parameter has a negative value'),
                    'data'        => []
                ];
                return;
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
        );
    }
}
