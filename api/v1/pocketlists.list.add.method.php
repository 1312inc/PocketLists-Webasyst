<?php

class pocketlistsListAddMethod extends pocketlistsApiAbstractMethod
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

        $err = false;
        $pocket_ids = array_unique(array_column($lists, 'pocket_id'));
        $pocket_access = pocketlistsRBAC::getAccessPocketForContact($this->getUser());

        if (!empty($pocket_ids)) {
            /** @var pocketlistsPocketModel $pocket_model */
            $pocket_model = pl2()->getModel(pocketlistsPocket::class);
            $pocket_ids = $pocket_model->select('id')->where('id IN (:pocket_ids)', ['pocket_ids' => $pocket_ids])->fetchAll(null, true);
        }

        /** validate */
        foreach ($lists as &$_list) {
            /** set default */
            $_list = [
                'id'                  => null,
                'pocket_id'           => ifset($_list, 'pocket_id', null),
                'sort'                => ifset($_list, 'sort', 0),
                'rank'                => ifset($_list, 'rank', ''),
                'type'                => ifset($_list, 'type', pocketlistsList::TYPE_CHECKLIST),
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
                'name'                => ifset($_list, 'name', null),
                'note'                => null,
                'due_date'            => null,
                'due_datetime'        => null,
                'location_id'         => null,
                'amount'              => null,
                'currency_iso3'       => null,
                'assigned_contact_id' => null,
                'repeat'              => null,
                'uuid'                => ifset($_list, 'uuid', null),
                'errors'              => [],
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

            if (!isset($_list['name'])) {
                $_list['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'name');
            } elseif (!is_string($_list['name'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'name');
            }

            if (!is_string($_list['type'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'type');
            } elseif (!in_array($_list['type'], ['checklist', 'notes'])) {
                $_list['errors'][] = _w('Unknown value type');
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

            if (isset($_list['uuid']) && !is_string($_list['uuid'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'uuid');
            }

            if (empty($_list['errors'])) {
                unset($_list['errors']);
            } else {
                $err = true;
            }
        }
        unset($_list);

        if (!$err) {
            /** @var pocketlistsListFactory $list_factory */
            $list_factory = pl2()->getEntityFactory(pocketlistsList::class);

            /** @var pocketlistsList $list */
            $list = pl2()->getEntityFactory(pocketlistsList::class)->createNew();

            foreach ($lists as &$_list) {
                $list_clone = clone $list;
                $list_clone->setName($_list['name'])
                    ->setType($_list['type'])
                    ->setPocketId($_list['pocket_id'])
                    ->setColor($_list['color'])
                    ->setIcon($_list['icon'])
                    ->setSort($_list['sort'])
                    ->setRank($_list['rank'])
                    ->setContact($this->getUser())
                    ->setCreateDatetime(date('Y-m-d H:i:s'))
                    ->setUuid($_list['uuid']);
                $list_factory->save($list_clone);
                $_list['key_item_id'] = $list_clone->getKeyItemId();
            }
        }

        $this->response = $this->filterFields(
            $lists,
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
            ],
            [
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
