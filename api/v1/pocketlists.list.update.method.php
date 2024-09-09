<?php

class pocketlistsListUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

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
            /** set default */
            $_list = [
                'id'                  => ifset($_list, 'id', null),
                'pocket_id'           => null,
                'sort'                => ifset($_list, 'sort', 0),
                'rank'                => ifset($_list, 'rank', ''),
                'type'                => null,
                'icon'                => ifset($_list, 'icon', pocketlistsList::DEFAULT_ICON),
                'archived'            => null,
                'hash'                => null,
                'color'               => ifset($_list, 'color', pocketlistsStoreColor::NONE),
                'passcode'            => null,
                'key_item_id'         => null,
                'contact_id'          => null,
                'parent_id'           => null,
                'has_children'        => null,
                'status'              => null,
                'priority'            => null,
                'calc_priority'       => null,
                'create_datetime'     => null,
                'update_datetime'     => null,
                'complete_datetime'   => null,
                'complete_contact_id' => null,
                'name'                => ifset($_list, 'name', ''),
                'note'                => null,
                'due_date'            => null,
                'due_datetime'        => null,
                'location_id'         => null,
                'amount'              => null,
                'currency_iso3'       => null,
                'assigned_contact_id' => null,
                'repeat'              => null,
                'uuid'                => null,
                'errors'              => [],
                'status_code'         => null,
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

            if (!is_string($_list['name'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'name');
            }

            if (!is_string($_list['icon'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'icon');
            }

            if (!is_string($_list['color'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'color');
            } elseif (!array_key_exists($_list['color'], pocketlistsStoreColor::getColors())) {
                $_list['errors'][] = _w('Unknown value color');
            }

            if (!is_numeric($_list['sort'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'sort');
            }

            if (!is_string($_list['rank'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'rank');
            }

            if (empty($_list['errors'])) {
                $_list = array_replace($lists_in_db[$_list['id']], array_filter($_list));
                unset($_list['errors']);
            } else {
                $_list['status_code'] = 'error';
            }
        }
        unset($_list);

        $lists_ok = array_filter($lists, function ($l) {
            return is_null($l['status_code']);
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
                $_list['status_code'] = 'ok';
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
