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
                throw new pocketlistsApiException(_w('Unknown value'), 400);
            } elseif ($location_id < 1) {
                throw new pocketlistsApiException(_w('Location not found'), 404);
            }
            $where .= ' AND id = i:location_id';
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
            throw new pocketlistsApiException(_w('Location not found'), 404);
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
