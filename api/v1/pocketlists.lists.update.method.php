<?php

class pocketlistsListsUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PATCH;

    public function execute()
    {
        $lists = $this->readBodyAsJson();

        if (empty($lists)) {
            throw new pocketlistsApiException(_w('Missing `data`'), 400);
        } elseif (!is_array($lists)) {
            throw new pocketlistsApiException(_w('Type error `data`'), 400);
        }

        $list_ids = array_unique(array_column($lists, 'id'));
        if (empty($list_ids)) {
            throw new pocketlistsApiException(_w('Type error `data`'), 400);
        }

        /** @var pocketlistsListModel $list_model */
        $list_model = pl2()->getModel(pocketlistsList::class);
        $lists_in_db = $list_model->getById($list_ids);
        $lists_in_db = (isset($lists_in_db['id']) ? [$lists_in_db['id'] => $lists_in_db] : $lists_in_db);
        $lists_id_available = pocketlistsRBAC::getAccessListForContact($this->getUser());

        $pockets_in_db = [];
        $pocket_ids = array_filter(array_unique(array_column($lists, 'pocket_id')), function ($i) {
            return $i > 0;
        });
        if ($pocket_ids) {
            $pocket_model = pl2()->getModel(pocketlistsPocket::class);
            /** @var pocketlistsPocket $pocket */
            $pockets_in_db = $pocket_model->getById($pocket_ids);
        }

        /** validate */
        foreach ($lists as &$_list) {
            $action = (ifset($_list, 'action', null) === self::ACTIONS[1] ? self::ACTIONS[1] : self::ACTIONS[0]);
            /** set default */
            $_list = [
                'action'                => $action,
                'id'                    => ifset($_list, 'id', null),
                'pocket_id'             => ifset($_list, 'pocket_id', null),
                'sort'                  => ifset($_list, 'sort', null),
                'rank'                  => ifset($_list, 'rank', null),
                'type'                  => null,
                'icon'                  => ifset($_list, 'icon', ($action === self::ACTIONS[0] ? null : pocketlistsList::DEFAULT_ICON)),
                'icon_url'              => null,
                'archived'              => 0,
                'hash'                  => null,
                'color'                 => ifset($_list, 'color', ($action === self::ACTIONS[0] ? null : pocketlistsStoreColor::NONE)),
                'passcode'              => null,
                'key_item_id'           => null,
                'contact_id'            => 0,
                'parent_id'             => 0,
                'has_children'          => 0,
                'status'                => 0,
                'priority'              => 0,
                'calc_priority'         => 0,
                'create_datetime'       => null,
                'update_datetime'       => date('Y-m-d H:i:s'),
                'complete_datetime'     => null,
                'complete_contact_id'   => null,
                'name'                  => ifset($_list, 'name', ($action === self::ACTIONS[0] ? null : '')),
                'note'                  => null,
                'due_date'              => null,
                'due_datetime'          => null,
                'client_touch_datetime' => ifset($_list, 'client_touch_datetime', null),
                'location_id'           => null,
                'amount'                => 0,
                'currency_iso3'         => null,
                'assigned_contact_id'   => null,
                'repeat'                => 0,
                'uuid'                  => null,
                'prev_list_id'          => (array_key_exists('prev_list_id', $_list) ? ifset($_list, 'prev_list_id', 0) : null),
                'success'               => true,
                'errors'                => []
            ];

            if (empty($_list['id'])) {
                $_list['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'id');
            } elseif (!is_numeric($_list['id'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'id');
            } elseif ($_list['id'] < 1 || !array_key_exists($_list['id'], $lists_in_db)) {
                $_list['errors'][] = _w('List not found');
            }

            if (!in_array($_list['id'], $lists_id_available)) {
                $_list['errors'][] = _w('List access denied');
            }

            if (isset($_list['pocket_id'])) {
                if (!is_numeric($_list['pocket_id'])) {
                    $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'pocket_id');
                } elseif ($_list['pocket_id'] < 1 || !array_key_exists($_list['pocket_id'], $pockets_in_db)) {
                    $_list['errors'][] = _w('Pocket not found');
                }
            }

            if (isset($_list['name']) && !is_string($_list['name'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'name');
            }

            if (isset($_list['icon']) && !is_string($_list['icon'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'icon');
            }

            if (isset($_list['color'])) {
                if (!is_string($_list['color'])) {
                    $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'color');
                } elseif (!array_key_exists($_list['color'], pocketlistsStoreColor::getColors())) {
                    $_list['errors'][] = _w('Unknown value color');
                }
            }

            if (isset($_list['client_touch_datetime'])) {
                if (!is_string($_list['client_touch_datetime'])) {
                    $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'client_touch_datetime');
                } else {
                    $dt = date_create($_list['client_touch_datetime'], new DateTimeZone('UTC'));
                    if ($dt) {
                        $_list['client_touch_datetime'] = $dt->format('Y-m-d H:i:s');
                    } else {
                        $_list['errors'][] = _w('Unknown value client_touch_datetime');
                    }
                }
            }

            if (isset($_list['sort']) && !is_numeric($_list['sort'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'sort');
            }

            if (isset($_list['rank'])) {
                if (!is_string($_list['rank'])) {
                    $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'rank');
                } elseif ($_list['rank'] !== '' && !pocketlistsSortRank::rankValidate($_list['rank'])) {
                    $_list['errors'][] = _w('Invalid rank value');
                }
            }

            if (empty($_list['errors'])) {
                if ($_list['action'] == self::ACTIONS[0]) {
                    // patch
                    $_list = array_replace($lists_in_db[$_list['id']], array_filter($_list, function ($l) {return !is_null($l);}));
                    if (isset($_list['prev_list_id'])) {
                        if ($_list['prev_list_id'] === 0) {
                            $_list['prev_list_id'] = null;
                        }
                        $_list['sort'] = null;
                        $_list['rank'] = null;
                    }
                } else {
                    // update
                    $_list += $lists_in_db[$_list['id']];
                    $_list['type'] = $lists_in_db[$_list['id']]['type'];
                    $_list['pocket_id'] = $lists_in_db[$_list['id']]['pocket_id'];
                    $_list['key_item_id'] = $lists_in_db[$_list['id']]['key_item_id'];
                    $_list['create_datetime'] = $lists_in_db[$_list['id']]['create_datetime'];
                }
            } else {
                $_list['success'] = false;
            }
        }
        unset($_list);

        $lists_ok = array_filter($lists, function ($l) {
            return $l['success'];
        });
        $lists_err = array_diff_key($lists, $lists_ok);
        if (!empty($lists_ok)) {
            $priority_count = [];
            $static_url = wa()->getAppStaticUrl(null, true).'img/listicons/';
            $summary = $list_model->query(
                $list_model->buildSqlComponents([
                    'select'   => ['*' => 'list_id, priority, COUNT(id) AS cnt'],
                    'from'     => ['pi' => 'pocketlists_item'],
                    'join'     => [],
                    'where'    => ['and' => [$list_ids ? 'list_id IN (i:ids)' : 'list_id IS NOT NULL']],
                    'group by' => ['list_id, priority'],
                    'order by' => ['list_id, priority']
                ]), ['ids' => $list_ids]
            )->fetchAll();
            if ($summary) {
                foreach ($summary as $_summ) {
                    $priority_count[$_summ['list_id']][$_summ['priority']] = $_summ['cnt'];
                }
                unset($summary);
            }

            /** @var pocketlistsListFactory $list_factory */
            $list_factory = pl2()->getEntityFactory(pocketlistsList::class);

            /** @var pocketlistsList $list */
            $list = $list_factory->createNew();
            $lists_ok = $this->sorting('list', $lists_ok);
            foreach ($lists_ok as &$_list) {
                $list_clone = clone $list;
                pl2()->getHydrator()->hydrate($list_clone, $_list);
                $list_clone->setUpdateDatetime(date('Y-m-d H:i:s'));
                if (!$list_factory->save($list_clone)) {
                    $_list['success'] = false;
                }

                $data = ifset($priority_count, $_list['id'], null);
                $max_priority = ($data ? max(array_keys($data)) : null);
                $max_priority = ($max_priority == 0 ? null : $max_priority);
                $_list['icon_url'] = $static_url.$_list['icon'];
                $_list['extended_data'] = [
                    'count' => ($data ? array_sum($data) : 0),
                    'priority_count' => (int) ifset($data, $max_priority, 0)
                ];
            }
            unset($_list);
            $this->saveLog(
                pocketlistsLog::ENTITY_LIST,
                pocketlistsLog::ACTION_UPDATE,
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
                'status' => 'int',
                'priority' => 'int',
                'calc_priority' => 'int',
                'create_datetime' => 'datetime',
                'update_datetime' => 'datetime',
                'complete_datetime' => 'datetime',
                'complete_contact_id' => 'int',
                'due_datetime' => 'datetime',
                'client_touch_datetime' => 'datetime',
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
