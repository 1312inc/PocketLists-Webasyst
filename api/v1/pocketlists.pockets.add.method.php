<?php

class pocketlistsPocketsAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $data = $this->readBodyAsJson();
        if (empty($data)) {
            $data = [[]];
        } elseif (!is_array($data)) {
            throw new pocketlistsApiException(_w('Type error `data`'), 400);
        }

        $uuids = array_column($data, 'uuid');
        if (!empty($uuids)) {
            $uuids = $this->getEntitiesByUuid('pocket', $uuids);
            $uuids = array_keys($uuids);
        }

        /** validate */
        foreach ($data as &$_pocket) {
            /** set default */
            $_pocket = [
                'id'                    => null,
                'pl_id'                 => 1312,
                'sort'                  => ifset($_pocket, 'sort', null),
                'rank'                  => ifset($_pocket, 'rank', null),
                'name'                  => ifset($_pocket, 'name', null),
                'color'                 => ifset($_pocket, 'color', pocketlistsStoreColor::NONE),
                'create_datetime'       => date('Y-m-d H:i:s'),
                'update_datetime'       => null,
                'activity_datetime'     => null,
                'client_touch_datetime' => ifset($_pocket, 'client_touch_datetime', null),
                'passcode'              => null,
                'uuid'                  => ifset($_pocket, 'uuid', null),
                'prev_pocket_id'        => ifset($_pocket, 'prev_pocket_id', null),
                'prev_pocket_uuid'      => ifset($_pocket, 'prev_pocket_uuid', null),
                'success'               => true,
                'errors'                => []
            ];

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

            if (isset($_pocket['uuid'])) {
                if (!is_string($_pocket['uuid'])) {
                    $_pocket['errors'][] = sprintf_wp('Invalid data type: “%s”', 'uuid');
                }
                if (in_array($_pocket['uuid'], $uuids)) {
                    $_pocket['errors'][] = _w('Pocket with UUID exists');
                }
            }

            if (!empty($_pocket['errors'])) {
                $_pocket['success'] = false;
            }
        }

        $pockets_ok = array_filter($data, function ($p) {
            return $p['success'];
        });
        $pockets_err = array_diff_key($data, $pockets_ok);
        if (!empty($pockets_ok)) {
            $user_id = $this->getUser()->getId();
            $cr_model = new waContactRightsModel();

            /** @var pocketlistsPocketFactory $pocket_factory */
            $pocket_factory = pl2()->getEntityFactory(pocketlistsPocket::class);

            /** @var pocketlistsPocket $pocket */
            $pocket = $pocket_factory->createNew();
            $pockets_ok = $this->sorting('pocket', $pockets_ok);
            foreach ($pockets_ok as &$_pocket) {
                $pocket_clone = clone $pocket;
                $pocket_clone->setName($_pocket['name'])
                    ->setSort($_pocket['sort'])
                    ->setRank($_pocket['rank'])
                    ->setColor($_pocket['color'])
                    ->setCreateDatetime($_pocket['create_datetime'])
                    ->setClientTouchDatetime($_pocket['client_touch_datetime'])
                    ->setUuid($_pocket['uuid']);

                if ($pocket_factory->save($pocket_clone)) {
                    $_pocket['id'] = $pocket_clone->getId();
                    $cr_model->save(
                        $user_id,
                        wa()->getApp(),
                        pocketlistsRBAC::POCKET_ITEM.'.'.$_pocket['id'],
                        pocketlistsRBAC::RIGHT_ADMIN
                    );
                } else {
                    $_pocket['success'] = false;
                }
            }
            $this->saveLog(
                pocketlistsLog::ENTITY_POCKET,
                pocketlistsLog::ACTION_ADD,
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
