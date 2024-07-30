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

        $err = false;
        $assign_contacts = [];
        $list_ids = array_unique(array_column($items, 'list_id'));
        $assigned_contact_ids = array_unique(array_column($items, 'assigned_contact_id'));

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
                'list_id'             => ifset($_item, 'list_id', 0),
                'name'                => ifset($_item, 'name', ''),
                'note'                => ifset($_item, 'note', ''),
                'assigned_contact_id' => ifset($_item, 'assigned_contact_id', null),
                'priority'            => ifset($_item, 'priority', 0),
                'contact_id'          => $this->getUser()->getId(),
                'attachments'         => ifset($_item, 'attachments', []),
                'create_datetime'     => date('Y-m-d H:i:s'),
                'due_datetime'        => ifset($_item, 'due_datetime', null),
                'due_date'            => null,
                'amount'              => 0,
                'repeat'              => 0,
                'errors'              => []
            ];

            if (empty($_item['list_id'])) {
                $_item['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'list_id');
            } elseif (!is_numeric($_item['list_id'])) {
                $_item['errors'][] = _w('Invalid type list_id');
            } elseif ($_item['list_id'] < 1 || !in_array($_item['list_id'], $list_ids)) {
                $_item['errors'][] = _w('List not found');
            }

            if (!is_string($_item['name'])) {
                $_item['errors'][] = _w('Invalid type name');
            }

            if ($_item['assigned_contact_id']) {
                if (!is_numeric($_item['assigned_contact_id'])) {
                    $_item['errors'][] = _w('Invalid type assigned_contact_id');
                } elseif (!array_key_exists($_item['assigned_contact_id'], $assign_contacts)) {
                    $_item['errors'][] = _w('Assigned contact not found');
                }
            }

            if ($_item['priority']) {
                if (!is_numeric($_item['priority'])) {
                    $_item['errors'][] = _w('Invalid type priority');
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
                    $_item['errors'][] = _w('Invalid type note');
                }
            }

            if (isset($_item['due_datetime'])) {
                if (!is_string($_item['due_datetime'])) {
                    $_item['errors'][] = _w('Invalid type due_datetime');
                } else {
                    $dt = date_create($_item['due_datetime']);
                    if ($dt) {
                        $_item['due_date'] = $dt->format('Y-m-d');
                        $_item['due_datetime'] = $dt->format('Y-m-d H:i:s');
                    } else {
                        $_item['errors'][] = _w('Unknown value due_datetime');
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
                    }
                } else {
                    $_item['errors'][] = _w('Invalid type files');
                }
            }

            if (empty($_item['errors'])) {
                unset($_item['errors']);
            } else {
                $err = true;
                $_item['attachments'] = [];
            }
        }

        if (!$err) {
            $item_model = pl2()->getModel(pocketlistsItem::class);
            $item_model->startTransaction();
            try {
                $result = $item_model->multipleInsert($items);
                if ($result->getResult()) {
                    $last_id = $result->lastInsertId();
                    $rows_count = $result->affectedRows();
                    if ($rows_count === count($items)) {
                        foreach ($items as &$_item) {
                            $_item['id'] = $last_id++;
                            if (!empty($_item['attachments'])) {
                                $_item['attachments'] = $this->updateFiles($_item['id'], $_item['attachments']);
                            }
                        }
                    } else {
                        throw new waAPIException('error', _w('Error on transaction'));
                    }
                    $item_model->commit();
                } else {
                    $item_model->rollback();
                }
            } catch (Exception $ex) {
                $item_model->rollback();
                throw new waAPIException('error', sprintf_wp('Error on transaction import save: %s', $ex->getMessage()), 400);
            }
        }

        $this->response = $this->filterFields(
            $items,
            [
                'id',
                'list_id',
                'contact_id',
                'parent_id',
                'sort',
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
                'favorite',
                'attachments_count',
                'comments_count',
                'linked_entities_count',
                'attachments'
            ],
            [
                'id' => 'int',
                'list_id' => 'int',
                'contact_id' => 'int',
                'parent_id' => 'int',
                'sort' => 'int',
                'has_children' => 'bool',
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
                'key_list_id' => 'int',
                'favorite' => 'bool',
                'attachments_count' => 'int',
                'comments_count' => 'int',
                'linked_entities_count' => 'int'
            ]
        );
    }
}
