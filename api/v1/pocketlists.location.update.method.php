<?php

class pocketlistsLocationUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PATCH;

    public function execute()
    {
        $locations = $this->readBodyAsJson();
        if (empty($locations)) {
            throw new waAPIException('required_param', _w('Missing data'), 400);
        } elseif (!is_array($locations)) {
            throw new waAPIException('type_error', _w('Type error data'), 400);
        }

        /** @var pocketlistsLocationModel $location_model */
        $location_model = pl2()->getModel(pocketlistsLocation::class);
        $location_ids = array_unique(array_column($locations, 'id'));
        $locations_in_db = $location_model->select('*')->where('id IN (i:location_ids)', ['location_ids' => $location_ids])->fetchAll('id');

        /** validate */
        foreach ($locations as &$_location) {
            /** set default */
            $_location = [
                'action'             => (ifset($_location, 'action', null) === self::ACTIONS[1] ? self::ACTIONS[1] : self::ACTIONS[0]),
                'id'                 => ifset($_location, 'id', null),
                'name'               => ifset($_location, 'name', null),
                'color'              => ifset($_location, 'color', null),
                'location_latitude'  => ifset($_location, 'location_latitude', null),
                'location_longitude' => ifset($_location, 'location_longitude', null),
                'location_radius'    => ifset($_location, 'location_radius', null),
                'uuid'               => ifset($_location, 'uuid', null),
                'errors'             => [],
                'status_code'        => 'ok',
            ];

            if (empty($_location['id'])) {
                $_location['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'id');
            } elseif (!is_numeric($_location['id'])) {
                $_location['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'id');
            } elseif (!array_key_exists($_location['id'], $locations_in_db)) {
                $_location['errors'][] = _w('Location not found');
            }

            if (isset($_location['name']) && !is_string($_location['name'])) {
                $_location['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'name');
            }

            if (isset($_location['color']) && !is_string($_location['color'])) {
                $_location['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'color');
            }

            if (isset($_location['location_latitude'])) {
                if (!is_numeric($_location['location_latitude'])) {
                    $_location['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'location_latitude');
                } elseif ($_location['location_latitude'] < -90 || $_location['location_latitude'] > 90) {
                    $_location['errors'][] = sprintf_wp('Invalid value “%s”', 'location_latitude');
                }
            }

            if (isset($_location['location_longitude'])) {
                if (!is_numeric($_location['location_longitude'])) {
                    $_location['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'location_longitude');
                } elseif ($_location['location_longitude'] < -180 || $_location['location_longitude'] > 180) {
                    $_location['errors'][] = sprintf_wp('Invalid value “%s”', 'location_longitude');
                }
            }

            if (isset($_location['location_radius'])) {
                if (!is_numeric($_location['location_radius'])) {
                    $_location['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'location_radius');
                } elseif ($_location['location_radius'] < 0) {
                    $_location['errors'][] = sprintf_wp('Invalid value “%s”', 'location_radius');
                }
            }

            if (empty($_location['errors'])) {
                if ($_location['action'] == self::ACTIONS[0]) {
                    // patch
                    $_location = array_replace($locations_in_db[$_location['id']], array_filter($_location, function ($l) {return !is_null($l);}));
                } else {
                    // update
                    $_location += $locations_in_db[$_location['id']];
                }
                unset($_location['errors']);
            } else {
                $_location['status_code'] = 'error';
            }
        }

        $locations_ok = array_filter($locations, function ($l) {
            return $l['status_code'] === 'ok';
        });
        $locations_err = array_diff_key($locations, $locations_ok);
        if (!empty($locations_ok)) {
            try {
                foreach ($locations_ok as &$_location_ok) {
                    $result = $location_model->updateById($_location_ok['id'], $_location_ok);
                    if (!$result) {
                        $_location_ok['status_code'] = 'error';
                        $_location_ok['errors'][] = _w('Failed to update');
                    }
                }
                unset($_location_ok);
                $this->saveLog(
                    pocketlistsLog::ENTITY_LOCATION,
                    pocketlistsLog::ACTION_UPDATE,
                    $locations_ok
                );
            } catch (Exception $ex) {
                throw new waAPIException('error', sprintf_wp('Error on transaction import save: %s', $ex->getMessage()), 400);
            }
        }

        $this->response = $this->filterFields(
            array_merge($locations_ok, $locations_err),
            [
                'id',
                'name',
                'color',
                'location_latitude',
                'location_longitude',
                'location_radius',
                'uuid',
                'errors',
                'status_code'
            ], [
                'id' => 'int',
                'location_latitude' => 'float',
                'location_longitude' => 'float',
                'location_radius' => 'int',
            ]
        );
    }
}
