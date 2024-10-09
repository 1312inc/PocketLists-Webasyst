<?php

class pocketlistsItemUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PATCH;

    public function execute()
    {
        $items = $this->readBodyAsJson();
        if (empty($items)) {
            throw new waAPIException('required_param', _w('Missing data'), 400);
        } elseif (!is_array($items)) {
            throw new waAPIException('type_error', _w('Type error data'), 400);
        }

        $assign_contacts = [];
        $user_id = $this->getUser()->getId();
        $item_ids = array_unique(array_column($items, 'id'));
        $list_ids = array_unique(array_column($items, 'list_id'));
        $assigned_contact_ids = array_unique(array_column($items, 'assigned_contact_id'));

        if (empty($item_ids)) {
            throw new waAPIException('type_error', _w('Type error data'), 400);
        }

        /** @var pocketlistsItemModel $item_model */
        $item_model = pl2()->getModel(pocketlistsItem::class);
        $items_in_db = $item_model->select('*')->where('id IN (:item_ids)', ['item_ids' => $item_ids])->fetchAll('id');
        $list_id_available = pocketlistsRBAC::getAccessListForContact($user_id);
        if (!empty($list_ids)) {
            /** @var pocketlistsListModel $list_model */
            $list_model = pl2()->getModel(pocketlistsList::class);
            $list_ids = $list_model->select('id')->where('id IN (:list_ids)', ['list_ids' => $list_ids])->fetchAll(null, true);
        }
        if (!empty($assigned_contact_ids)) {
            /** @var pocketlistsContact $_assign_contact */
            foreach (pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithIds($assigned_contact_ids) as $_assign_contact) {
                if ($_assign_contact->isExists()) {
                    $assign_contacts[$_assign_contact->getId()] = $_assign_contact;
                }
            }
        }

        /** validate */
        foreach ($items as &$_item) {
            /** set default */
            $_item = [
                'action'              => (ifset($_item, 'action', null) === self::ACTIONS[1] ? self::ACTIONS[1] : self::ACTIONS[0]),
                'id'                  => ifset($_item, 'id', null),
                'list_id'             => ifset($_item, 'list_id', null),
                'contact_id'          => $user_id,
                'parent_id'           => 0,
                'sort'                => ifset($_item, 'sort', null),
                'rank'                => ifset($_item, 'rank', null),
                'has_children'        => 0,
                'status'              => 0,
                'priority'            => ifset($_item, 'priority', null),
                'calc_priority'       => 0,
                'create_datetime'     => null,
                'update_datetime'     => date('Y-m-d H:i:s'),
                'complete_datetime'   => null,
                'complete_contact_id' => null,
                'name'                => ifset($_item, 'name', null),
                'note'                => ifset($_item, 'note', null),
                'due_date'            => ifset($_item, 'due_date', null),
                'due_datetime'        => ifset($_item, 'due_datetime', null),
                'location_id'         => null,
                'amount'              => 0,
                'currency_iso3'       => null,
                'assigned_contact_id' => ifset($_item, 'assigned_contact_id', null),
                'repeat'              => 0,
                'key_list_id'         => null,
                'uuid'                => ifset($_item, 'uuid', null),
                'prev_item_id'        => (array_key_exists('prev_item_id', $_item) ? ifset($_item, 'prev_item_id', 0) : null),
                'attachments'         => ifset($_item, 'attachments', []),
                'errors'              => [],
                'status_code'         => 'ok',
            ];

            if (empty($_item['id'])) {
                $_item['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'id');
            } elseif (!is_numeric($_item['id'])) {
                $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'item_id');
            } elseif (!array_key_exists($_item['id'], $items_in_db)) {
                $_item['errors'][] = _w('Item not found');
            }

            if ($_item['list_id']) {
                if (!is_numeric($_item['list_id'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'list_id');
                } elseif (!in_array($_item['list_id'], $list_ids)) {
                    $_item['errors'][] = _w('List not found');
                } elseif (!in_array($_item['list_id'], $list_id_available)) {
                    $_item['errors'][] = _w('Access denied');
                }
            }

            if ($_item['assigned_contact_id']) {
                if (!is_numeric($_item['assigned_contact_id'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'assigned_contact_id');
                } elseif (!array_key_exists($_item['assigned_contact_id'], $assign_contacts)) {
                    $_item['errors'][] = _w('Assigned contact not found');
                }
            }

            if ($_item['priority']) {
                if (!is_numeric($_item['priority'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'priority');
                } elseif (!in_array($_item['priority'], [1, 2, 3, 4, 5])) {
                    $_item['errors'][] = _w('Unknown value priority');
                }
            }

            if (isset($_item['name']) && !is_string($_item['name'])) {
                $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'name');
            }

            if (isset($_item['note']) && !is_string($_item['note'])) {
                $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'note');
            }

            if (isset($_item['sort']) && !is_numeric($_item['sort'])) {
                $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'sort');
            }

            if (isset($_item['rank'])) {
                if (!is_string($_item['rank'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'rank');
                } elseif ($_item['rank'] !== '' && !pocketlistsSortRank::rankValidate($_item['rank'])) {
                    $_item['errors'][] = _w('Invalid rank value');
                }
            }

            if ($_item['due_datetime']) {
                if (!is_string($_item['due_datetime'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'due_datetime');
                } else {
                    $dt = date_create($_item['due_datetime']);
                    if ($dt) {
                        $_item['due_date'] = $dt->format('Y-m-d');
                        $_item['due_datetime'] = $dt->format('Y-m-d H:i:s');
                    } else {
                        $_item['errors'][] = _w('Unknown value due_datetime');
                    }
                }
            } elseif ($_item['due_date']) {
                if (!is_string($_item['due_date'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'due_date');
                } else {
                    $dt = date_create($_item['due_date']);
                    if ($dt) {
                        $_item['due_date'] = $dt->format('Y-m-d');
                        $_item['due_datetime'] = '';
                    } else {
                        $_item['errors'][] = _w('Unknown value due_date');
                    }
                }
            }

            if (empty($_item['errors'])) {
                if ($_item['action'] == self::ACTIONS[0]) {
                    // patch
                    $_item = array_replace($items_in_db[$_item['id']], array_filter($_item, function ($i) {return !is_null($i);}));
                    if (isset($_item['prev_item_id'])) {
                        if ($_item['prev_item_id'] === 0) {
                            $_item['prev_item_id'] = null;
                        }
                        $_item['sort'] = null;
                        $_item['rank'] = null;
                    }
                } else {
                    // update
                    $_item += $items_in_db[$_item['id']];
                }
                unset($_item['errors']);
            } else {
                $_item['attachments'] = [];
                $_item['status_code'] = 'error';
            }
        }

        $items_ok = array_filter($items, function ($i) {
            return $i['status_code'] === 'ok';
        });
        $items_err = array_diff_key($items, $items_ok);
        if (!empty($items_ok)) {
            $item_model = pl2()->getModel(pocketlistsItem::class);
            $items_ok = $this->sorting('item', $items_ok);
            try {
                foreach ($items_ok as &$_item_ok) {
                    $result = $item_model->updateById($_item_ok['id'], $_item_ok);
                    if ($result) {
                        if (!empty($_item_ok['attachments'])) {
                            $_item_ok['attachments'] = $this->updateFiles($_item_ok['id'], $_item_ok['attachments']);
                        }
                    } else {
                        $_item_ok['status_code'] = 'error';
                        $_item_ok['errors'][] = _w('Failed to update');
                    }
                }
                unset($_item_ok);
                $this->saveLog(
                pocketlistsLog::ENTITY_ITEM,
                    pocketlistsLog::ACTION_UPDATE,
                    $items_ok
                );
            } catch (Exception $ex) {
                throw new waAPIException('error', sprintf_wp('Error on transaction import save: %s', $ex->getMessage()), 400);
            }
        }

        $this->response = $this->filterFields(
            array_merge($items_ok, $items_err),
            [
                'id',
                'list_id',
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
                'note',
                'due_date',
                'due_datetime',
                'location_id',
                'amount',
                'currency_iso3',
                'assigned_contact_id',
                'repeat',
                'key_list_id',
                'uuid',
                'prev_item_id',
                'attachments',
                'errors',
                'status_code'
            ], [
                'id' => 'int',
                'list_id' => 'int',
                'contact_id' => 'int',
                'parent_id' => 'int',
                'sort' => 'int',
                'has_children' => 'int',
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
                'key_list_id' => 'int'
        ]);
    }
}
