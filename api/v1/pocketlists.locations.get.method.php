<?php

class pocketlistsLocationsGetMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $location_id = $this->get('location_id');
        $starting_from = $this->get('starting_from');
        $nearby = $this->get('nearby');
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        $select = 'SQL_CALC_FOUND_ROWS *';
        $where = '1 = 1';
        if (isset($location_id)) {
            if (!is_numeric($location_id)) {
                throw new pocketlistsApiException(_w('Unknown value'), 400);
            } elseif ($location_id < 1) {
                throw new pocketlistsApiException(_w('Location not found'), 404);
            }
            $where .= ' AND id = i:location_id';
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
            $where .= ' AND (update_datetime >= s:starting_from OR create_datetime >= s:starting_from)';
            $order = 'update_datetime DESC, id';
        } else {
            $order = 'id';
        }
        if (isset($nearby)) {
            $location = explode(',', $nearby);
            if (count($location) !== 2) {
                throw new pocketlistsApiException(_w('Not two values nearby'), 400);
            }
            list($latitude, $longitude) = $location;
            if (!is_numeric($latitude) || !is_numeric($longitude)) {
                throw new pocketlistsApiException(_w('Type error nearby value'), 400);
            } elseif ($latitude < -90 || $latitude > 90) {
                throw new pocketlistsApiException(sprintf_wp('Invalid value “%s”', 'nearby latitude'), 400);
            } elseif ($longitude < -180 || $longitude > 180) {
                throw new pocketlistsApiException(sprintf_wp('Invalid value “%s”', 'nearby longitude'), 400);
            }
            // вычисляется только примерное расстояние между точками
            $radius_earth = 6371000;
            $select .= ", CEILING(SQRT(POW(PI()*(s:latitude-pl.location_latitude)/180, 2)+POW(PI()*(s:longitude-pl.location_longitude)/180, 2))*$radius_earth) AS meter";
            $order = 'case when meter is null then 1 else 0 end, meter';
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
            SELECT $select FROM pocketlists_location pl
            WHERE $where
            ORDER BY $order
            LIMIT i:offset, i:limit
            ", [
                'location_id' => $location_id,
                'starting_from' => $starting_from,
                'latitude' => ifset($latitude),
                'longitude' => ifset($longitude),
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
                'create_datetime',
                'update_datetime',
                'uuid'
            ], [
                'id' => 'int',
                'location_latitude' => 'float',
                'location_longitude' => 'float',
                'location_radius' => 'int',
                'create_datetime' => 'datetime',
                'update_datetime' => 'datetime'
            ]
        );
    }
}
