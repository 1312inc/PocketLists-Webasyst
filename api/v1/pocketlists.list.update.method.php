<?php

class pocketlistsListUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PATCH;

    public function execute()
    {
        $lists = $this->readBodyAsJson();

        if (empty($lists)) {
            throw new waAPIException('required_param', _w('Missing data'), 400);
        } elseif (!is_array($lists)) {
            throw new waAPIException('type_error', _w('Type error data'), 400);
        }

        $list_ids = array_unique(array_column($lists, 'id'));

        if (empty($list_ids)) {
            throw new waAPIException('type_error', _w('Type error data'), 400);
        }

        /** @var pocketlistsListModel $list_model */
        $list_model = pl2()->getModel(pocketlistsList::class);
        $lists_in_db = $list_model->query(
            $list_model->buildSqlComponents(
                $list_model->getAllListsSqlComponents(0, $list_ids)
            ),
            ['list_ids' => $list_ids]
        )->fetchAll('id');
        $lists_id_available = pocketlistsRBAC::getAccessListForContact($this->getUser());

        /** validate */
        foreach ($lists as &$_list) {
            $action = (ifset($_list, 'action', null) === self::ACTIONS[1] ? self::ACTIONS[1] : self::ACTIONS[0]);
            /** set default */
            $_list = [
                'action'              => $action,
                'id'                  => ifset($_list, 'id', null),
                'pocket_id'           => null,
                'sort'                => ifset($_list, 'sort', 0),
                'rank'                => ifset($_list, 'rank', ''),
                'type'                => null,
                'icon'                => ifset($_list, 'icon', ($action === self::ACTIONS[0] ? null : pocketlistsList::DEFAULT_ICON)),
                'archived'            => 0,
                'hash'                => null,
                'color'               => ifset($_list, 'color', ($action === self::ACTIONS[0] ? null : pocketlistsStoreColor::NONE)),
                'passcode'            => null,
                'key_item_id'         => null,
                'contact_id'          => 0,
                'parent_id'           => 0,
                'has_children'        => 0,
                'status'              => 0,
                'priority'            => 0,
                'calc_priority'       => 0,
                'create_datetime'     => null,
                'update_datetime'     => date('Y-m-d H:i:s'),
                'complete_datetime'   => null,
                'complete_contact_id' => null,
                'name'                => ifset($_list, 'name', ($action === self::ACTIONS[0] ? null : '')),
                'note'                => null,
                'due_date'            => null,
                'due_datetime'        => null,
                'location_id'         => null,
                'amount'              => 0,
                'currency_iso3'       => null,
                'assigned_contact_id' => null,
                'repeat'              => 0,
                'uuid'                => null,
                'errors'              => [],
                'status_code'         => 'ok',
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

            if (!is_numeric($_list['sort'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'sort');
            }

            if (!is_string($_list['rank'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'rank');
            }

            if (empty($_list['errors'])) {
                if ($_list['action'] == self::ACTIONS[0]) {
                    // patch
                    $_list = array_replace($lists_in_db[$_list['id']], array_filter($_list));
                } else {
                    // update
                    $_list += $lists_in_db[$_list['id']];
                    $_list['type'] = $lists_in_db[$_list['id']]['type'];
                    $_list['pocket_id'] = $lists_in_db[$_list['id']]['pocket_id'];
                    $_list['key_item_id'] = $lists_in_db[$_list['id']]['key_item_id'];
                    $_list['create_datetime'] = $lists_in_db[$_list['id']]['create_datetime'];
                }
                unset($_list['errors']);
            } else {
                $_list['status_code'] = 'error';
            }
        }
        unset($_list);

        $lists_ok = array_filter($lists, function ($l) {
            return $l['status_code'] === 'ok';
        });
        $lists_err = array_diff_key($lists, $lists_ok);
        if (!empty($lists_ok)) {
            /** @var pocketlistsListFactory $list_factory */
            $list_factory = pl2()->getEntityFactory(pocketlistsList::class);

            /** @var pocketlistsList $list */
            $list = pl2()->getEntityFactory(pocketlistsList::class)->createNew();
            foreach ($lists_ok as &$_list) {
                $list_clone = clone $list;
                pl2()->getHydrator()->hydrate($list_clone, $_list);
                $list_clone->setUpdateDatetime(date('Y-m-d H:i:s'));
                $list_factory->save($list_clone);
            }
        }

        $this->response = $this->filterFields(
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
                'location_id',
                'amount',
                'currency_iso3',
                'assigned_contact_id',
                'repeat',
                'uuid',
                'pocket_id',
                'type',
                'icon',
                'archived',
                'hash',
                'color',
                'passcode',
                'key_item_id',
                'errors',
                'status_code',
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
