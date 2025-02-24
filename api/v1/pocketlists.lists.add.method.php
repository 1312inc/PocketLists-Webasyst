<?php

class pocketlistsListsAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $lists = $this->readBodyAsJson();

        if (empty($lists)) {
            throw new pocketlistsApiException(_w('Missing `data`'), 400);
        } elseif (!is_array($lists)) {
            throw new pocketlistsApiException(_w('Type error `data`'), 400);
        }

        $user_id = $this->getUser()->getId();
        $pocket_ids = array_unique(array_column($lists, 'pocket_id'));
        $pocket_access = pocketlistsRBAC::getAccessPocketForContact($this->getUser());
        $uuids = array_column($lists, 'uuid');

        if (!empty($pocket_ids)) {
            /** @var pocketlistsPocketModel $pocket_model */
            $pocket_model = pl2()->getModel(pocketlistsPocket::class);
            $pocket_ids = $pocket_model->select('id')->where('id IN (:pocket_ids)', ['pocket_ids' => $pocket_ids])->fetchAll(null, true);
        }
        if (!empty($uuids)) {
            $uuids = $this->getEntitiesByUuid('list', $uuids);
            $uuids = array_keys($uuids);
        }

        /** validate */
        foreach ($lists as &$_list) {
            /** set default */
            $_list = [
                'id'                    => null,
                'pocket_id'             => ifset($_list, 'pocket_id', null),
                'sort'                  => ifset($_list, 'sort', 0),
                'rank'                  => ifset($_list, 'rank', ''),
                'type'                  => ifset($_list, 'type', pocketlistsList::TYPE_CHECKLIST),
                'icon'                  => ifset($_list, 'icon', pocketlistsList::DEFAULT_ICON),
                'icon_url'              => null,
                'archived'              => 0,
                'hash'                  => null,
                'color'                 => ifset($_list, 'color', pocketlistsStoreColor::NONE),
                'passcode'              => null,
                'key_item_id'           => null,
                'contact_id'            => $user_id,
                'parent_id'             => 0,
                'has_children'          => 0,
                'status'                => 0,
                'priority'              => 0,
                'calc_priority'         => 0,
                'create_datetime'       => date('Y-m-d H:i:s'),
                'update_datetime'       => null,
                'activity_datetime'     => null,
                'complete_datetime'     => null,
                'complete_contact_id'   => null,
                'name'                  => ifset($_list, 'name', null),
                'note'                  => '',
                'due_date'              => null,
                'due_datetime'          => null,
                'client_touch_datetime' => ifset($_list, 'client_touch_datetime', null),
                'location_id'           => null,
                'amount'                => 0,
                'currency_iso3'         => null,
                'assigned_contact_id'   => null,
                'repeat'                => 0,
                'uuid'                  => ifset($_list, 'uuid', null),
                'prev_list_id'          => ifset($_list, 'prev_list_id', null),
                'prev_list_uuid'        => ifset($_list, 'prev_list_uuid', null),
                'success'               => true,
                'errors'                => []
            ];

            if (!isset($_list['pocket_id'])) {
                $_list['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'pocket_id');
            } elseif (!is_numeric($_list['pocket_id'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'pocket_id');
            } elseif ($_list['pocket_id'] < 1 || !in_array($_list['pocket_id'], $pocket_ids)) {
                $_list['errors'][] = _w('List not found');
            } elseif (!in_array($_list['pocket_id'], $pocket_access)) {
                $_list['errors'][] = _w('Pocket access denied');
            }

            if (isset($_list['name']) && !is_string($_list['name'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'name');
            }

            if (!is_string($_list['type'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'type');
            } elseif (!in_array($_list['type'], [pocketlistsList::TYPE_CHECKLIST, pocketlistsList::TYPE_NOTES])) {
                $_list['errors'][] = _w('Unknown value type');
            }

            if (isset($_list['icon']) && !is_string($_list['icon'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'icon');
            }

            if (!is_string($_list['color'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'color');
            } elseif (!array_key_exists($_list['color'], pocketlistsStoreColor::getColors())) {
                $_list['errors'][] = _w('Unknown value color');
            }

            if (isset($_list['client_touch_datetime'])) {
                if (!is_string($_list['client_touch_datetime'])) {
                    $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'client_touch_datetime');
                } else {
                    $dt = date_create($_list['client_touch_datetime']);
                    if ($dt) {
                        $_list['client_touch_datetime'] = $dt->format('Y-m-d H:i:s');
                    } else {
                        $_list['errors'][] = _w('Unknown value client_touch_datetime');
                    }
                }
            }

            if (!empty($_list['sort']) && !is_numeric($_list['sort'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'sort');
            }

            if ($_list['rank'] !== '') {
                if (!is_string($_list['rank'])) {
                    $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'rank');
                } elseif ($_list['rank'] !== '' && !pocketlistsSortRank::rankValidate($_list['rank'])) {
                    $_list['errors'][] = _w('Invalid rank value');
                }
            }

            if (isset($_list['uuid'])) {
                if (!is_string($_list['uuid'])) {
                    $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'uuid');
                } elseif (in_array($_list['uuid'], $uuids)) {
                    $_list['errors'][] = _w('List with UUID exists');
                }
            }

            if (!empty($_list['errors'])) {
                $_list['success'] = false;
            }
        }
        unset($_list);

        $lists_ok = array_filter($lists, function ($l) {
            return $l['success'];
        });
        $lists_err = array_diff_key($lists, $lists_ok);
        if (!empty($lists_ok)) {
            $static_url = wa()->getAppStaticUrl(null, true).'img/listicons/';
            /** @var pocketlistsListFactory $list_factory */
            $list_factory = pl2()->getEntityFactory(pocketlistsList::class);

            /** @var pocketlistsList $list */
            $list = $list_factory->createNew();
            $lists_ok = $this->sorting('list', $lists_ok);
            foreach ($lists_ok as &$_list) {
                $list_clone = clone $list;
                $list_clone->setName($_list['name'])
                    ->setType($_list['type'])
                    ->setPocketId($_list['pocket_id'])
                    ->setColor($_list['color'])
                    ->setIcon($_list['icon'])
                    ->setClientTouchDatetime($_list['client_touch_datetime'])
                    ->setSort($_list['sort'])
                    ->setRank($_list['rank'])
                    ->setContact($this->getUser())
                    ->setCreateDatetime($_list['create_datetime'])
                    ->setUuid($_list['uuid']);
                if (!$list_factory->save($list_clone)) {
                    $_list['success'] = false;
                }
                $_list['id'] = $list_clone->getId();
                $_list['key_item_id'] = $list_clone->getKeyItemId();
                $_list['icon_url'] = $static_url.$_list['icon'];
                if (count($lists) === 1) {
                    list($teammates) = $this->getTeammates([$user_id]);
                } else {
                    $teammates = [];
                }
                $_list['extended_data'] = [
                    'items_count'           => 0,
                    'items_priority_count'  => 0,
                    'items_priority_value'  => 0,
                    'items_completed_count' => 0,
                    'users'                 => $teammates
                ];
            }
            unset($_list);

            if ($pocket_ids = array_filter(array_unique(array_column($lists_ok, 'pocket_id')))) {
                pl2()->getModel(pocketlistsPocket::class)->updateById(
                    $pocket_ids,
                    ['activity_datetime' => date('Y-m-d H:i:s')]
                );
            }

            $this->saveLog(
                pocketlistsLog::ENTITY_LIST,
                pocketlistsLog::ACTION_ADD,
                array_filter($lists_ok, function ($l) {
                    return $l['success'];
                })
            );
        }

        $this->response['data'] = $this->responseWrapper(
            array_merge($lists_ok, $lists_err),
            [
                'id',
                'contact_id',
                'parent_id',
                'sort',
                'rank',
                'has_children',
                'status',
                'priority',
                'calc_priority',
                'create_datetime',
                'update_datetime',
                'activity_datetime',
                'complete_datetime',
                'complete_contact_id',
                'name',
                'due_date',
                'due_datetime',
                'client_touch_datetime',
                'location_id',
                'amount',
                'currency_iso3',
                'assigned_contact_id',
                'repeat',
                'uuid',
                'pocket_id',
                'type',
                'icon',
                'icon_url',
                'archived',
                'hash',
                'color',
                'passcode',
                'key_item_id',
                'extended_data'
            ], [
                'id' => 'int',
                'contact_id' => 'int',
                'parent_id' => 'int',
                'sort' => 'int',
                'has_children' => 'int',
                'status' => 'int',
                'priority' => 'int',
                'calc_priority' => 'int',
                'create_datetime' => 'datetime',
                'update_datetime' => 'datetime',
                'activity_datetime' => 'datetime',
                'complete_datetime' => 'datetime',
                'complete_contact_id' => 'int',
                'due_datetime' => 'datetime',
                'client_touch_datetime' => 'dateiso',
                'location_id' => 'int',
                'amount' => 'float',
                'assigned_contact_id' => 'int',
                'repeat' => 'int',
                'pocket_id' => 'int',
                'archived' => 'int',
                'key_item_id' => 'int'
            ]
        );
    }
}
