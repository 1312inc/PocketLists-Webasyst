<?php

class pocketlistsLocationsGetMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $location_id = $this->get('location_id');
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        $where = '1 = 1';
        if (isset($location_id)) {
            if (!is_numeric($location_id)) {
                $this->http_status_code = 400;
                $this->response = [
                    'status_code' => 'error',
                    'error'       => _w('Unknown value'),
                    'data'        => []
                ];
                return;
            } elseif ($location_id < 1) {
                $this->http_status_code = 400;
                $this->response = [
                    'status_code' => 'error',
                    'error'       => _w('Location not found'),
                    'data'        => []
                ];
                return;
            }
            $where .= ' AND id = i:location_id';
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

        $pllm = pl2()->getModel(pocketlistsLocation::class);
        $locations = $pllm->query("
            SELECT SQL_CALC_FOUND_ROWS * FROM pocketlists_location
            WHERE $where
            ORDER BY id DESC
            LIMIT i:offset, i:limit
            ", [
                'location_id' => $location_id,
                'limit'  => $limit,
                'offset' => $offset
            ]
        )->fetchAll();
        $total_count = (int) $pllm->query('SELECT FOUND_ROWS()')->fetchField();

        if (empty($locations) && isset($location_id)) {
            $this->http_status_code = 404;
            $this->response = [
                'status_code' => 'error',
                'error'       => _w('Location not found'),
                'data'        => []
            ];
            return;
        }

        $this->response['meta'] = [
            'offset' => $offset,
            'limit'  => $limit,
            'count'  => $total_count
        ];
        $this->response['data'] = $this->responseListWrapper(
            $locations,
            [
                'id',
                'name',
                'color',
                'location_latitude',
                'location_longitude',
                'location_radius',
                'uuid'
            ], [
                'id' => 'int',
                'location_latitude' => 'float',
                'location_longitude' => 'float',
                'location_radius' => 'int',
            ]
        );
    }
}
