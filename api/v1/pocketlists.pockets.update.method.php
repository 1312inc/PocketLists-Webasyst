<?php

class pocketlistsPocketsUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PATCH;

    public function execute()
    {
        $pockets = $this->readBodyAsJson();

        if (empty($pockets)) {
            throw new pocketlistsApiException(_w('Missing `data`'), 400);
        } elseif (!is_array($pockets)) {
            throw new pocketlistsApiException(_w('Type error `data`'), 400);
        }

        $pockets_in_db = [];
        $pocket_ids = array_filter(array_unique(array_column($pockets, 'id')), function ($i) {
            return $i > 0;
        });
        if ($pocket_ids) {
            $pocket_model = pl2()->getModel(pocketlistsPocket::class);
            /** @var pocketlistsPocket $pocket */
            $pockets_in_db = $pocket_model->getById($pocket_ids);
        }

        /** validate */
        foreach ($pockets as &$_pocket) {
            /** set default */
            $_pocket = [
                'action'                => (ifset($_pocket, 'action', null) === self::ACTIONS[1] ? self::ACTIONS[1] : self::ACTIONS[0]),
                'id'                    => ifset($_pocket, 'id', null),
                'pl_id'                 => 1312,
                'sort'                  => ifset($_pocket, 'sort', null),
                'rank'                  => ifset($_pocket, 'rank', null),
                'name'                  => ifset($_pocket, 'name', null),
                'color'                 => ifset($_pocket, 'color', pocketlistsStoreColor::NONE),
                'create_datetime'       => null,
                'update_datetime'       => date('Y-m-d H:i:s'),
                'client_touch_datetime' => ifset($_pocket, 'client_touch_datetime', null),
                'passcode'              => null,
                'uuid'                  => null,
                'prev_pocket_id'        => ifset($_pocket, 'prev_pocket_id', null),
                'success'               => true,
                'errors'                => []
            ];

            if (!isset($_pocket['id'])) {
                $_pocket['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'id');
            } elseif (!is_numeric($_pocket['id'])) {
                $_pocket['errors'][] = sprintf_wp('Invalid data type: “%s”', 'id');
            } elseif ($_pocket['id'] < 1 || !array_key_exists($_pocket['id'], $pockets_in_db)) {
                $_pocket['errors'][] = _w('Pocket not found');
            }

            if (isset($_pocket['name']) && !is_string($_pocket['name'])) {
                $_pocket['errors'][] = sprintf_wp('Invalid data type: “%s”', 'name');
            }

            if (isset($_pocket['color']) && (!is_string($_pocket['color']) || !array_key_exists($_pocket['color'], pocketlistsStoreColor::getColors()))) {
                $_pocket['errors'][] = _w('Unknown color');
            }

            if (isset($_pocket['sort']) && !is_numeric($_pocket['sort'])) {
                $_pocket['errors'][] = sprintf_wp('Invalid data type: “%s”', 'sort');
            }

            if (isset($_pocket['rank'])) {
                if (!is_string($_pocket['rank'])) {
                    $_pocket['errors'][] = sprintf_wp('Invalid data type: “%s”', 'rank');
                } elseif ($_pocket['rank'] !== '' && !pocketlistsSortRank::rankValidate($_pocket['rank'])) {
                    $_pocket['errors'][] = _w('Invalid rank value');
                }
            }

            if (isset($_pocket['client_touch_datetime'])) {
                if (!is_string($_pocket['client_touch_datetime'])) {
                    $_pocket['errors'][] = sprintf_wp('Invalid data type: “%s”', 'client_touch_datetime');
                } else {
                    $dt = date_create($_pocket['client_touch_datetime']);
                    if ($dt) {
                        $_pocket['client_touch_datetime'] = $dt->format('Y-m-d H:i:s');
                    } else {
                        $_pocket['errors'][] = _w('Invalid value client_touch_datetime');
                    }
                }
            }

            if (empty($_pocket['errors'])) {
                if ($_pocket['action'] == self::ACTIONS[0]) {
                    // patch
                    $_pocket = array_replace($pockets_in_db[$_pocket['id']], array_filter($_pocket, function ($p) {return !is_null($p);}));
                    if (isset($_pocket['prev_pocket_id'])) {
                        if ($_pocket['prev_pocket_id'] === 0) {
                            $_pocket['prev_pocket_id'] = null;
                        }
                        $_pocket['sort'] = null;
                        $_pocket['rank'] = null;
                    }
                } else {
                    // update
                    $_pocket += $pockets_in_db[$_pocket['id']];
                }
            } else {
                $_pocket['success'] = false;
            }
        }
        unset($_pocket);

        $pockets_ok = array_filter($pockets, function ($p) {
            return $p['success'];
        });
        $pockets_err = array_diff_key($pockets, $pockets_ok);
        if (!empty($pockets_ok)) {
            /** @var pocketlistsPocketFactory $pocket_factory */
            $pocket_factory = pl2()->getEntityFactory(pocketlistsPocket::class);

            /** @var pocketlistsPocket $pocket */
            $pocket = $pocket_factory->createNew();
            $pockets_ok = $this->sorting('pocket', $pockets_ok);
            foreach ($pockets_ok as &$_pocket) {
                $pocket_clone = clone $pocket;
                pl2()->getHydrator()->hydrate($pocket_clone, $_pocket);
                if (!$pocket_factory->save($pocket_clone)) {
                    $_pocket['success'] = false;
                }
            }

            $this->saveLog(
                pocketlistsLog::ENTITY_POCKET,
                pocketlistsLog::ACTION_UPDATE,
                array_filter($pockets_ok, function ($p) {
                    return $p['success'];
                })
            );
        }

        $this->response['data'] = $this->responseWrapper(
            array_merge($pockets_ok, $pockets_err),
            [
                'id',
                'sort',
                'rank',
                'name',
                'color',
                'create_datetime',
                'update_datetime',
                'activity_datetime',
                'client_touch_datetime',
                'passcode',
                'uuid'
            ], [
                'id' => 'int',
                'sort' => 'int',
                'create_datetime' => 'datetime',
                'update_datetime' => 'datetime',
                'activity_datetime' => 'datetime',
                'client_touch_datetime' => 'dateiso'
            ]
        );
    }
}
