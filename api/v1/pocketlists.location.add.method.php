<?php

class pocketlistsLocationAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $locations = $this->readBodyAsJson();
        if (empty($locations)) {
            throw new waAPIException('required_param', _w('Missing data'), 400);
        } elseif (!is_array($locations)) {
            throw new waAPIException('type_error', _w('Type error data'), 400);
        }

        $uuids = array_column($locations, 'uuid');
        if (!empty($uuids)) {
            $uuids = $this->getEntitiesByUuid('location', $uuids);
            $uuids = array_keys($uuids);
        }

        /** validate */
        foreach ($locations as &$_location) {
            /** set default */
            $_location = [
                'id'                 => null,
                'name'               => ifset($_location, 'name', null),
                'color'              => ifset($_location, 'color', null),
                'location_latitude'  => ifset($_location, 'location_latitude', null),
                'location_longitude' => ifset($_location, 'location_longitude', null),
                'location_radius'    => ifset($_location, 'location_radius', null),
                'uuid'               => ifset($_location, 'uuid', null),
                'errors'             => [],
                'status_code'        => null,
            ];

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

            if (isset($_location['uuid'])) {
                if (!is_string($_location['uuid'])) {
                    $_location['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'uuid');
                } elseif (in_array($_location['uuid'], $uuids)) {
                    $_location['errors'][] = _w('Location with UUID exists');
                }
            }

            if (empty($_location['errors'])) {
                unset($_location['errors']);
            } else {
                $_location['status_code'] = 'error';
            }
        }

        $locations_ok = array_filter($locations, function ($l) {
            return is_null($l['status_code']);
        });
        $locations_err = array_diff_key($locations, $locations_ok);
        if (!empty($locations_ok)) {
            $location_model = pl2()->getModel(pocketlistsLocation::class);
            try {
                $result = $location_model->multipleInsert($locations_ok);
                if ($result->getResult()) {
                    $last_id = $result->lastInsertId();
                    $rows_count = $result->affectedRows();
                    if ($rows_count === count($locations_ok)) {
                        foreach ($locations_ok as &$_locations) {
                            $_locations['id'] = $last_id++;
                            $_locations['status_code'] = 'ok';
                        }
                        unset($_locations);
                        $this->saveLog(
                            pocketlistsLog::ENTITY_LOCATION,
                            pocketlistsLog::ACTION_ADD,
                            $locations_ok
                        );
                    } else {
                        throw new waAPIException('error', _w('Error on transaction'));
                    }
                } else {
                    throw new waAPIException('error', _w('Error on transaction'));
                }
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
