<?php

class pocketlistsLogGetMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $starting_from = $this->get('starting_from');
        $offset = $this->get('offset');
        $limit = $this->get('limit');

        if (!isset($starting_from)) {
            $this->http_status_code = 400;
            $this->response = [
                'status_code' => 'error',
                'error'       => sprintf_wp('Missing required parameter: “%s”.', 'starting_from'),
                'data'        => []
            ];
            return;
        } elseif (!is_numeric($starting_from)) {
            $this->http_status_code = 400;
            $this->response = [
                'status_code' => 'error',
                'error'       => sprintf_wp('Type error parameter: “%s”.', 'starting_from'),
                'data'        => []
            ];
            return;
        } elseif ($starting_from < 1) {
            $this->http_status_code = 400;
            $this->response = [
                'status_code' => 'error',
                'error'       => _w('The parameter has a negative value'),
                'data'        => []
            ];
            return;
        }

        if (isset($limit)) {
            if (!is_numeric($limit)) {
                $this->http_status_code = 400;
                $this->response = [
                    'status_code' => 'error',
                    'error'       => sprintf_wp('Type error parameter: “%s”.', 'limit'),
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
                    'error'       => sprintf_wp('Type error parameter: “%s”.', 'offset'),
                    'data'        => []
                ];
                return;
            } elseif ($offset < 1) {
                $this->http_status_code = 400;
                $this->response = [
                    'status_code' => 'error',
                    'error'       => _w('The parameter has a negative value'),
                    'data'        => []
                ];
                return;
            }
        } else {
            $offset = 0;
        }

        /** @var pocketlistsLogModel $log_model */
        $log_model = pl2()->getModel(pocketlistsLog::class);
        $query_components = $log_model->getQueryComponents();
        $query_components['where']['and'][] = 'l.create_datetime >= s:starting_from';
        $logs = $log_model->query(
            $log_model->buildSqlComponents($query_components, $limit, $offset, true),
            [
                'starting_from' => date('Y-m-d H:i:s', $starting_from),
            ]
        )->fetchAll();
        $total_count = (int) $log_model->query('SELECT FOUND_ROWS()')->fetchField();

        $this->response['meta'] = [
            'offset' => $offset,
            'limit'  => $limit,
            'count'  => $total_count
        ];
        $this->response['data'] = $this->responseListWrapper(
            $logs,
            [
                'id',
                'action',
                'entity_type',
                'contact_id',
                'pocket_id',
                'list_id',
                'item_id',
                'comment_id',
                'attachment_id',
                'location_id',
                'additional_id',
                'assigned_contact_id',
                'create_datetime',
                'params'
            ],
            [
                'id' => 'int',
                'contact_id' => 'int',
                'pocket_id' => 'int',
                'list_id' => 'int',
                'item_id' => 'int',
                'comment_id' => 'int',
                'attachment_id' => 'int',
                'location_id' => 'int',
                'additional_id' => 'int',
                'assigned_contact_id' => 'int',
                'create_datetime' => 'datetime'
            ]
        );
    }
}
