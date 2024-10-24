<?php

class pocketlistsItemAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $items = $this->readBodyAsJson();
        if (empty($items)) {
            throw new waAPIException('required_param', _w('Missing data'), 400);
        } elseif (!is_array($items)) {
            throw new waAPIException('type_error', _w('Type error data'), 400);
        }

        $assign_contacts = [];
        $attachment_uuids = [];
        $list_ids = array_unique(array_filter(array_column($items, 'list_id')));
        $assigned_contact_ids = array_unique(array_filter(array_column($items, 'assigned_contact_id')));
        $uuids = array_column($items, 'uuid');
        $attachments = array_column($items, 'attachments');

        if (!empty($list_ids)) {
            /** @var pocketlistsListModel $list_model */
            $list_model = pl2()->getModel(pocketlistsList::class);
            $list_ids = $list_model->select('id')
                ->where('id IN (:list_ids)', ['list_ids' => $list_ids])
                ->fetchAll(null, true);
        }
        if (!empty($assigned_contact_ids)) {
            /** @var pocketlistsContact $_assign_contact */
            foreach (pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithIds($assigned_contact_ids) as $_assign_contact) {
                if ($_assign_contact->isExists()) {
                    $assign_contacts[$_assign_contact->getId()] = $_assign_contact;
                }
            }
        }
        if (!empty($uuids)) {
            $uuids = $this->getEntitiesByUuid('item', $uuids);
            $uuids = array_keys($uuids);
        }
        if (!empty($attachments)) {
            foreach ($attachments as $_attachment) {
                $attachment_uuids = array_merge($attachment_uuids, array_column($_attachment, 'uuid'));
            }
            unset($attachments);
            $attachment_uuids = $this->getEntitiesByUuid('attachment', $attachment_uuids);
            $attachment_uuids = array_keys($attachment_uuids);
        }

        /** validate */
        $user_id = $this->getUser()->getId();
        foreach ($items as &$_item) {
            /** set default */
            $_item = [
                'id'                    => null,
                'list_id'               => ifset($_item, 'list_id', null),
                'contact_id'            => $user_id,
                'parent_id'             => 0,
                'sort'                  => ifset($_item, 'sort', null),
                'rank'                  => ifset($_item, 'rank', null),
                'has_children'          => 0,
                'status'                => 0,
                'priority'              => ifset($_item, 'priority', 0),
                'calc_priority'         => 0,
                'create_datetime'       => date('Y-m-d H:i:s'),
                'update_datetime'       => null,
                'complete_datetime'     => null,
                'complete_contact_id'   => null,
                'name'                  => ifset($_item, 'name', ''),
                'note'                  => ifset($_item, 'note', ''),
                'due_date'              => ifset($_item, 'due_date', null),
                'due_datetime'          => ifset($_item, 'due_datetime', null),
                'client_touch_datetime' => ifset($_item, 'client_touch_datetime', null),
                'location_id'           => null,
                'amount'                => 0,
                'currency_iso3'         => null,
                'assigned_contact_id'   => ifset($_item, 'assigned_contact_id', null),
                'repeat'                => 0,
                'key_list_id'           => null,
                'uuid'                  => ifset($_item, 'uuid', null),
                'attachments'           => ifset($_item, 'attachments', []),
                'prev_item_id'          => ifset($_item, 'prev_item_id', null),
                'prev_item_uuid'        => ifset($_item, 'prev_item_uuid', null),
                'errors'                => [],
                'status_code'           => null,
            ];

            if (isset($_item['list_id'])) {
                if (!is_numeric($_item['list_id'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'list_id');
                } elseif ($_item['list_id'] < 1 || !in_array($_item['list_id'], $list_ids)) {
                    $_item['errors'][] = _w('List not found');
                }
            }

            if (!is_string($_item['name'])) {
                $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'name');
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
            } else {
                $match_priority = pocketlistsNaturalInput::matchPriority($_item['name']);
                if ($match_priority) {
                    $_item['name'] = $match_priority['name'];
                    $_item['priority'] = (int) $match_priority['priority'];
                }
            }

            $match_note = pocketlistsNaturalInput::matchNote($_item['name']);
            if ($match_note) {
                if (empty($_item['note'])) {
                    $_item['name'] = $match_note['name'];
                    $_item['note'] = $match_note['note'];
                } elseif (!is_string($_item['note'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'note');
                }
            }

            if (isset($_item['due_datetime'])) {
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
            } elseif (isset($_item['due_date'])) {
                if (!is_string($_item['due_date'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'due_date');
                } else {
                    $dt = date_create($_item['due_date']);
                    if ($dt) {
                        $_item['due_date'] = $dt->format('Y-m-d');
                    } else {
                        $_item['errors'][] = _w('Unknown value due_date');
                    }
                }
            }

            if (isset($_item['client_touch_datetime'])) {
                if (!is_string($_item['client_touch_datetime'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'client_touch_datetime');
                } else {
                    $dt = date_create($_item['client_touch_datetime']);
                    if ($dt) {
                        $_item['client_touch_datetime'] = $dt->format('Y-m-d H:i:s');
                    } else {
                        $_item['errors'][] = _w('Unknown value client_touch_datetime');
                    }
                }
            }

            if (!empty($_item['attachments'])) {
                if (is_array($_item['attachments'])) {
                    foreach ($_item['attachments'] as $_file) {
                        if (empty($_file['file'])) {
                            $_item['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'file');
                        }
                        if (empty($_file['file_name'])) {
                            $_item['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'file_name');
                        }
                        if (!empty($_file['uuid']) && in_array($_file['uuid'], $attachment_uuids)) {
                            $_item['errors'][] = _w('Attachment with UUID exists');
                        }
                    }
                } else {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'files');
                }
            }

            if (isset($_item['uuid'])) {
                if (!is_string($_item['uuid'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'uuid');
                } elseif (in_array($_item['uuid'], $uuids)) {
                    $_item['errors'][] = _w('Item with UUID exists');
                }
            }

            if (empty($_item['errors'])) {
                unset($_item['errors']);
            } else {
                $_item['attachments'] = [];
                $_item['status_code'] = 'error';
            }
        }

        $items_ok = array_filter($items, function ($i) {
            return is_null($i['status_code']);
        });
        $items_err = array_diff_key($items, $items_ok);
        if (!empty($items_ok)) {
            $item_model = pl2()->getModel(pocketlistsItem::class);
            $items_ok = $this->sorting('item', $items_ok);
            try {
                $result = $item_model->multipleInsert($items_ok);
                if ($result->getResult()) {
                    $last_id = $result->lastInsertId();
                    $rows_count = $result->affectedRows();
                    if ($rows_count === count($items_ok)) {
                        foreach ($items_ok as &$_item) {
                            $_item['id'] = $last_id++;
                            $_item['status_code'] = 'ok';
                            if (!empty($_item['attachments'])) {
                                $_item['attachments'] = $this->updateFiles($_item['id'], $_item['attachments']);
                            }
                        }
                        unset($_item);
                        $this->saveLog(
                            pocketlistsLog::ENTITY_ITEM,
                            pocketlistsLog::ACTION_ADD,
                            $items_ok
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
                'client_touch_datetime',
                'location_id',
                'amount',
                'currency_iso3',
                'assigned_contact_id',
                'repeat',
                'key_list_id',
                'uuid',
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
                'client_touch_datetime' => 'datetime',
                'location_id' => 'int',
                'amount' => 'float',
                'assigned_contact_id' => 'int',
                'repeat' => 'int',
                'key_list_id' => 'int'
            ]
        );
    }
}
