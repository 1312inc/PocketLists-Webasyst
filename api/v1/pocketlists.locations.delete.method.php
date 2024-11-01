<?php

class pocketlistsLocationsDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $data = $this->get('id');

        if (empty($data)) {
            $this->http_status_code = 400;
            $this->response = [
                'status_code' => 'error',
                'error'       => sprintf_wp('Missing required parameter: “%s”.', 'id'),
                'data'        => []
            ];
            return;
        } elseif (!is_array($data)) {
            $this->http_status_code = 400;
            $this->response = [
                'status_code' => 'error',
                'error'       => sprintf_wp('Invalid type %s', 'id'),
                'data'        => []
            ];
            return;
        }

        $locations = [];
        $location_ids = array_unique(array_filter($data));

        /** @var pocketlistsLocationFactory $plf */
        $plf = pl2()->getEntityFactory(pocketlistsLocation::class);
        if (!empty($location_ids)) {
            $locations = $plf->findById($location_ids);
            $location_ids = [];

            /** @var pocketlistsLocation $l */
            foreach ((array) $locations as $l) {
                $location_ids[] = $l->getId();
            }
        }

        // validate
        foreach ($data as &$_location) {
            /** set default */
            $_location = [
                'id'      => ifempty($_location),
                'success' => null,
                'errors'  => [],
            ];

            if (empty($_location['id'])) {
                $_location['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'id');
            } elseif (!is_numeric($_location['id'])) {
                $_location['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'id');
            } elseif (!in_array($_location['id'], $location_ids)) {
                $_location['errors'][] = _w('Location not found');
            }

            if (!empty($_location['errors'])) {
                $_location['success'] = false;
            }
        }

        $locations_ok = array_filter($data, function ($l) {
            return is_null($l['success']);
        });
        $locations_err = array_diff_key($data, $locations_ok);
        if (!empty($locations_ok)) {
            $logs = [];
            foreach ($locations as $location) {
                try {
                    $id = $location->getId();
                    if ($plf->delete($location)) {
                        $success = true;
                        $logs[] = [
                            'id'   => $location->getId(),
                            'name' => $location->getName()
                        ];
                    } else {
                        $success = false;
                    }
                    foreach ($locations_ok as &$_location_ok) {
                        if ($_location_ok['id'] == $id) {
                            $_location_ok['success'] = $success;
                            break;
                        }
                    }
                } catch (waException $we) {

                }
            }
            $this->saveLog(
                pocketlistsLog::ENTITY_LOCATION,
                pocketlistsLog::ACTION_DELETE,
                $logs
            );
        }

        $this->response['data'] = $this->responseWrapper(
            array_merge($locations_ok, $locations_err),
            [
                'id'
            ], [
                'id' => 'int'
            ]
        );
    }
}
