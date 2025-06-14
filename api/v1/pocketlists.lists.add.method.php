<?php

class pocketlistsListsAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $lists = $this->readBodyAsJson();

        if (empty($lists)) {
            $lists = [[]];
        } elseif (!is_array($lists)) {
            throw new pocketlistsApiException(_w('Type error `data`'), 400);
        }

        $assign_contacts = [];
        $user_id = $this->getUser()->getId();
        $pocket_ids = array_unique(array_column($lists, 'pocket_id'));
        $pocket_access = pocketlistsRBAC::getAccessPocketForContact($this->getUser());
        $assigned_contact_ids = array_unique(array_filter(array_column($lists, 'assigned_contact_id')));
        $uuids = array_column($lists, 'uuid');

        if (!empty($pocket_ids)) {
            /** @var pocketlistsPocketModel $pocket_model */
            $pocket_model = pl2()->getModel(pocketlistsPocket::class);
            $pocket_ids = $pocket_model->select('id')->where('id IN (:pocket_ids)', ['pocket_ids' => $pocket_ids])->fetchAll(null, true);
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
            $uuids = $this->getEntitiesByUuid('list', $uuids);
            $uuids = array_keys($uuids);
        }

        /** validate */
        foreach ($lists as &$_list) {
            /** set default */
            $_list = [
                'id'                    => null,
                'pocket_id'             => ifset($_list, 'pocket_id', null),
                'sort'                  => ifset($_list, 'sort', null),
                'rank'                  => ifset($_list, 'rank', null),
                'type'                  => ifset($_list, 'type', pocketlistsList::TYPE_CHECKLIST),
                'icon'                  => ifset($_list, 'icon', null),
                'icon_url'              => null,
                'private'               => ifset($_list, 'private', 0),
                'archived'              => ifset($_list, 'archived', 0),
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
                'due_date'              => ifset($_list, 'due_date', null),
                'due_datetime'          => ifset($_list, 'due_datetime', null),
                'client_touch_datetime' => ifset($_list, 'client_touch_datetime', null),
                'location_id'           => null,
                'amount'                => 0,
                'currency_iso3'         => null,
                'assigned_contact_id'   => ifset($_list, 'assigned_contact_id', null),
                'repeat_frequency'      => ifset($_list, 'repeat_frequency', 0),
                'repeat_interval'       => ifset($_list, 'repeat_interval', null),
                'repeat_occurrence'     => ifset($_list, 'repeat_occurrence', null),
                'favorite'              => ifset($_list, 'favorite', 0),
                'uuid'                  => ifset($_list, 'uuid', null),
                'pro_label_id'          => null,
                'prev_list_id'          => ifset($_list, 'prev_list_id', null),
                'prev_list_uuid'        => ifset($_list, 'prev_list_uuid', null),
                'success'               => true,
                'errors'                => []
            ];

            if (isset($_list['pocket_id'])) {
                if (!is_numeric($_list['pocket_id'])) {
                    $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'pocket_id');
                } elseif ($_list['pocket_id'] < 1 || !in_array($_list['pocket_id'], $pocket_ids)) {
                    $_list['errors'][] = _w('Pocket not found');
                } elseif (!in_array($_list['pocket_id'], $pocket_access)) {
                    $_list['errors'][] = _w('Pocket access denied');
                }
            }

            if (isset($_list['name']) && !is_string($_list['name'])) {
                $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'name');
            }

            if (!is_string($_list['type'])) {
                $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'type');
            } elseif (!in_array($_list['type'], [pocketlistsList::TYPE_CHECKLIST, pocketlistsList::TYPE_NOTES])) {
                $_list['errors'][] = _w('Invalid value type');
            }

            if (!is_numeric($_list['private'])) {
                $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'private');
            }

            if (!is_numeric($_list['archived'])) {
                $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'archived');
            }

            if (isset($_list['icon']) && !is_string($_list['icon'])) {
                $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'icon');
            }

            if (!is_string($_list['color'])) {
                $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'color');
            } elseif (!array_key_exists($_list['color'], pocketlistsStoreColor::getColors())) {
                $_list['errors'][] = _w('Invalid value color');
            }

            if (isset($_list['assigned_contact_id'])) {
                if (!is_numeric($_list['assigned_contact_id'])) {
                    $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'assigned_contact_id');
                } elseif ($_list['assigned_contact_id'] < 1) {
                    $_list['errors'][] = _w('Contact not found');
                } elseif (!array_key_exists($_list['assigned_contact_id'], $assign_contacts)) {
                    $_list['errors'][] = _w('Assigned contact not found');
                }
            }

            if ($_list['repeat_frequency'] &&!is_numeric($_list['repeat_frequency'])) {
                $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'repeat_frequency');
            }

            if (isset($_list['repeat_interval'])) {
                if (!is_string($_list['repeat_interval'])) {
                    $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'repeat_interval');
                } elseif (!in_array($_list['repeat_interval'], pocketlistsItem::REPEAT_INTERVAL)) {
                    $_list['errors'][] = _w('Invalid value repeat_interval');
                }
            }

            if (isset($_list['repeat_occurrence']) && !is_numeric($_list['repeat_occurrence'])) {
                $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'repeat_occurrence');
            }

            if ($_list['favorite']) {
                if (!is_numeric($_list['favorite'])) {
                    $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'favorite');
                } elseif (!in_array($_list['favorite'], [0, 1])) {
                    $_list['errors'][] = _w('Invalid value favorite');
                }
            }

            if (isset($_list['due_datetime'])) {
                if (!is_string($_list['due_datetime'])) {
                    $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'due_datetime');
                } else {
                    if ($dt = $this->convertDatetimeToServer($_list['due_datetime'])) {
                        $_list['due_date'] = date('Y-m-d', strtotime($dt));
                        $_list['due_datetime'] = $dt;
                    } else {
                        $_list['errors'][] = _w('Invalid value due_datetime');
                    }
                }
            } elseif (isset($_list['due_date'])) {
                if (!is_string($_list['due_date'])) {
                    $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'due_date');
                } else {
                    $dt = date_create($_list['due_date']);
                    if ($dt) {
                        $_list['due_date'] = $dt->format('Y-m-d');
                    } else {
                        $_list['errors'][] = _w('Invalid value due_date');
                    }
                }
            }

            if (isset($_list['client_touch_datetime'])) {
                if (!is_string($_list['client_touch_datetime'])) {
                    $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'client_touch_datetime');
                } else {
                    $dt = date_create($_list['client_touch_datetime']);
                    if ($dt) {
                        $_list['client_touch_datetime'] = $dt->format('Y-m-d H:i:s');
                    } else {
                        $_list['errors'][] = _w('Invalid value client_touch_datetime');
                    }
                }
            }

            if (isset($_list['sort']) && !is_numeric($_list['sort'])) {
                $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'sort');
            }

            if (isset($_list['rank'])) {
                if (!is_string($_list['rank'])) {
                    $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'rank');
                } elseif ($_list['rank'] !== '' && !pocketlistsSortRank::rankValidate($_list['rank'])) {
                    $_list['errors'][] = _w('Invalid rank value');
                }
            }

            if (isset($_list['uuid'])) {
                if (!is_string($_list['uuid'])) {
                    $_list['errors'][] = sprintf_wp('Invalid data type: “%s”', 'uuid');
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
            $favorites = [];
            $cr_model = new waContactRightsModel();
            $static_url = wa()->getAppStaticUrl(null, true).'img/listicons/';

            /** @var pocketlistsListFactory $list_factory */
            $list_factory = pl2()->getEntityFactory(pocketlistsList::class);

            /** @var pocketlistsList $list */
            $list = $list_factory->createNew();
            $lists_ok = $this->sorting('list', $lists_ok);
            foreach ($lists_ok as &$_list) {
                if (!isset($_list['icon'])) {
                    $_list['icon'] = pocketlistsNaturalInput::iconMatchCategory($_list['name']);
                }
                $list_clone = clone $list;
                $list_clone->setName($_list['name'])
                    ->setType($_list['type'])
                    ->setPocketId($_list['pocket_id'])
                    ->setPrivate($_list['private'])
                    ->setArchived($_list['archived'])
                    ->setColor($_list['color'])
                    ->setAssignedContactId($_list['assigned_contact_id'])
                    ->setRepeatFrequency($_list['repeat_frequency'])
                    ->setRepeatInterval($_list['repeat_interval'])
                    ->setRepeatOccurrence($_list['repeat_occurrence'])
                    ->setIcon($_list['icon'])
                    ->setDueDate($_list['due_date'])
                    ->setDueDatetime($_list['due_datetime'])
                    ->setClientTouchDatetime($_list['client_touch_datetime'])
                    ->setSort($_list['sort'])
                    ->setRank($_list['rank'])
                    ->setContact($this->getUser())
                    ->setCreateDatetime($_list['create_datetime'])
                    ->setUuid($_list['uuid']);
                if ($list_factory->save($list_clone)) {
                    if (!pocketlistsRBAC::isAdmin()) {
                        /* add access for user */
                        $cr_model->save(
                            $user_id,
                            pocketlistsHelper::APP_ID,
                            'list.'.$list_clone->getId(),
                            pocketlistsRBAC::RIGHT_ACCESS
                        );
                    }
                    if ($_list['assigned_contact_id']) {
                        /* add access for assigned user */
                        $cr_model->save(
                            $_list['assigned_contact_id'],
                            pocketlistsHelper::APP_ID,
                            'list.'.$list_clone->getId(),
                            pocketlistsRBAC::RIGHT_ACCESS
                        );
                    }
                    if ($_list['favorite']) {
                        $favorites[] = [
                            'item_id'    => $list_clone->getKeyItemId(),
                            'contact_id' => $user_id,
                        ];
                    }
                    if (!$list_clone->isPrivate()) {
                        (new pocketlistsNotificationAboutNewList())->notifyAboutNewList($list_clone);
                    }
                    $this->systemLogAction(pocketlistsLogAction::LIST_CREATED, ['list_id' => $list_clone->getId()]);
                } else {
                    $_list['success'] = false;
                }
                $_list['id'] = $list_clone->getId();
                $_list['private'] = $list_clone->isPrivate();
                $_list['key_item_id'] = $list_clone->getKeyItemId();
                $_list['icon_url'] = $static_url.$_list['icon'];
                $_list['extended_data'] = [
                    'items_count'           => 0,
                    'items_priority_count'  => 0,
                    'items_priority_value'  => 0,
                    'items_completed_count' => 0,
                    'users'                 => [$user_id]
                ];
            }
            unset($_list);

            if ($favorites) {
                $uf_model = pl2()->getModel(pocketlistsUserFavorites::class);
                $uf_model->multipleInsert($favorites, waModel::INSERT_IGNORE);
            }

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
                'repeat_frequency',
                'repeat_interval',
                'repeat_occurrence',
                'favorite',
                'uuid',
                'pocket_id',
                'type',
                'icon',
                'icon_url',
                'private',
                'archived',
                'hash',
                'color',
                'passcode',
                'key_item_id',
                'pro_label_id',
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
                'repeat_frequency' => 'int',
                'repeat_occurrence' => 'int',
                'favorite' => 'int',
                'pocket_id' => 'int',
                'private' => 'int',
                'archived' => 'int',
                'key_item_id' => 'int',
                'pro_label_id' => 'int'
            ]
        );
    }
}
