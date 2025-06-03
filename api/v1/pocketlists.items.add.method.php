<?php

class pocketlistsItemsAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $items = $this->readBodyAsJson();
        if (empty($items)) {
            throw new pocketlistsApiException(_w('Missing `data`'), 400);
        } elseif (!is_array($items)) {
            throw new pocketlistsApiException(_w('Type error `data`'), 400);
        }

        $lists = [];
        $assign_contacts = [];
        $attachment_uuids = [];
        $list_ids = array_unique(array_filter(array_column($items, 'list_id')));
        $location_ids = array_unique(array_filter(array_column($items, 'location_id')));
        $label_ids = array_unique(array_filter(array_column($items, 'pro_label_id')));
        $assigned_contact_ids = array_unique(array_filter(array_column($items, 'assigned_contact_id')));
        $uuids = array_column($items, 'uuid');
        $attachments = array_column($items, 'attachments');

        $access_list_ids = pocketlistsRBAC::getAccessListForContact(pl2()->getUser()->getId());
        if (!empty($list_ids)) {
            /** @var pocketlistsListModel $list_model */
            $list_model = pl2()->getModel(pocketlistsList::class);
            $lists = $list_model->select('id, private, archived')
                ->where('id IN (:list_ids)', ['list_ids' => array_intersect($access_list_ids, $list_ids)])
                ->fetchAll('id');
        }
        if (!empty($location_ids)) {
            /** @var pocketlistsLocationModel $location_model */
            $location_model = pl2()->getModel(pocketlistsLocation::class);
            $location_ids = $location_model->select('id')
                ->where('id IN (:list_ids)', ['list_ids' => $location_ids])
                ->fetchAll(null, true);
        }
        if (!empty($label_ids)) {
            /** @var pocketlistsLabelModel $label_model */
            $label_model = pl2()->getModel(pocketlistsLabel::class);
            $label_ids = $label_model->select('id')
                ->where('id IN (:list_ids)', ['list_ids' => $label_ids])
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
                'archived'              => null,
                'has_children'          => 0,
                'status'                => (ifset($_item, 'status', 0) ? pocketlistsItem::STATUS_DONE : pocketlistsItem::STATUS_UNDONE),
                'priority'              => ifset($_item, 'priority', 0),
                'calc_priority'         => 0,
                'create_datetime'       => date('Y-m-d H:i:s'),
                'update_datetime'       => null,
                'activity_datetime'     => null,
                'complete_datetime'     => null,
                'complete_contact_id'   => null,
                'name'                  => ifset($_item, 'name', ''),
                'note'                  => ifset($_item, 'note', ''),
                'due_date'              => ifset($_item, 'due_date', null),
                'due_datetime'          => ifset($_item, 'due_datetime', null),
                'client_touch_datetime' => ifset($_item, 'client_touch_datetime', null),
                'location_id'           => ifset($_item, 'location_id', null),
                'amount'                => 0,
                'currency_iso3'         => null,
                'assigned_contact_id'   => ifset($_item, 'assigned_contact_id', null),
                'repeat_frequency'      => ifset($_item, 'repeat_frequency', 0),
                'repeat_interval'       => ifset($_item, 'repeat_interval', null),
                'repeat_occurrence'     => ifset($_item, 'repeat_occurrence', null),
                'favorite'              => ifset($_item, 'favorite', 0),
                'key_list_id'           => null,
                'uuid'                  => ifset($_item, 'uuid', null),
                'pro_label_id'          => ifset($_item, 'pro_label_id', null),
                'tags'                  => $this->tagFilter(ifset($_item, 'tags', [])),
                'attachments'           => ifset($_item, 'attachments', []),
                'external_links'        => ifset($_item, 'external_links', []),
                'prev_item_id'          => ifset($_item, 'prev_item_id', null),
                'prev_item_uuid'        => ifset($_item, 'prev_item_uuid', null),
                'success'               => true,
                'errors'                => []
            ];

            if (isset($_item['list_id'])) {
                if (!is_numeric($_item['list_id'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'list_id');
                } elseif (!in_array($_item['list_id'], $access_list_ids)) {
                    $_item['errors'][] = _w('List access denied');
                } elseif ($_item['list_id'] < 1 || !array_key_exists($_item['list_id'], $lists)) {
                    $_item['errors'][] = _w('List not found');
                }
            }

            if (!is_string($_item['name'])) {
                $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'name');
            }

            if (isset($_item['sort']) && !is_numeric($_item['sort'])) {
                $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'sort');
            }

            if (isset($_item['rank'])) {
                if (!is_string($_item['rank'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'rank');
                } elseif ($_item['rank'] !== '' && !pocketlistsSortRank::rankValidate($_item['rank'])) {
                    $_item['errors'][] = _w('Invalid rank value');
                }
            }

            if ($_item['assigned_contact_id']) {
                if (!is_numeric($_item['assigned_contact_id'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'assigned_contact_id');
                } elseif (!array_key_exists($_item['assigned_contact_id'], $assign_contacts)) {
                    $_item['errors'][] = _w('Assigned contact not found');
                }
            }

            if ($_item['repeat_frequency'] &&!is_numeric($_item['repeat_frequency'])) {
                $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'repeat_frequency');
            }

            if (isset($_item['repeat_interval'])) {
                if (!is_string($_item['repeat_interval'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'repeat_interval');
                } elseif (!in_array($_item['repeat_interval'], pocketlistsItem::REPEAT_INTERVAL)) {
                    $_item['errors'][] = _w('Invalid value repeat_interval');
                }
            }

            if (isset($_item['repeat_occurrence']) && !is_numeric($_item['repeat_occurrence'])) {
                $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'repeat_occurrence');
            }

            if ($_item['favorite']) {
                if (!is_numeric($_item['favorite'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'favorite');
                } elseif (!in_array($_item['favorite'], [0, 1])) {
                    $_item['errors'][] = _w('Invalid value favorite');
                }
            }

            if ($_item['priority']) {
                if (!is_numeric($_item['priority'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'priority');
                } elseif (!in_array($_item['priority'], [1, 2, 3, 4, 5])) {
                    $_item['errors'][] = _w('Invalid value priority');
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
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'note');
                }
            }

            if (isset($_item['due_datetime'])) {
                if (!is_string($_item['due_datetime'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'due_datetime');
                } else {
                    if ($dt = $this->convertDatetimeToServer($_item['due_datetime'])) {
                        $_item['due_date'] = date('Y-m-d', strtotime($dt));
                        $_item['due_datetime'] = $dt;
                    } else {
                        $_item['errors'][] = _w('Invalid value due_datetime');
                    }
                }
            } elseif (isset($_item['due_date'])) {
                if (!is_string($_item['due_date'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'due_date');
                } else {
                    $dt = date_create($_item['due_date']);
                    if ($dt) {
                        $_item['due_date'] = $dt->format('Y-m-d');
                    } else {
                        $_item['errors'][] = _w('Invalid value due_date');
                    }
                }
            }

            if (isset($_item['client_touch_datetime'])) {
                if (!is_string($_item['client_touch_datetime'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'client_touch_datetime');
                } else {
                    $dt = date_create($_item['client_touch_datetime']);
                    if ($dt) {
                        $_item['client_touch_datetime'] = $dt->format('Y-m-d H:i:s');
                    } else {
                        $_item['errors'][] = _w('Invalid value client_touch_datetime');
                    }
                }
            }

            if (isset($_item['location_id'])) {
                if (!is_numeric($_item['location_id'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'location_id');
                } elseif ($_item['location_id'] < 1 || !in_array($_item['location_id'], $location_ids)) {
                    $_item['errors'][] = _w('Location not found');
                }
            }

            if (isset($_item['uuid'])) {
                if (!is_string($_item['uuid'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'uuid');
                } elseif (in_array($_item['uuid'], $uuids)) {
                    $_item['errors'][] = _w('Item with UUID exists');
                }
            }

            if (isset($_item['pro_label_id'])) {
                if (!is_numeric($_item['pro_label_id'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'pro_label_id');
                } elseif ($_item['pro_label_id'] < 1 || !in_array($_item['pro_label_id'], $label_ids)) {
                    $_item['errors'][] = _w('Label not found');
                }
            }

            if (!empty($_item['tags'])) {
                if (!is_array($_item['tags'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'tags');
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
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'files');
                }
            }

            if (!empty($_item['external_links'])) {
                if (is_array($_item['external_links'])) {
                    foreach ($_item['external_links'] as $_external_link) {
                        if (!isset($_external_link['app_id'], $_external_link['entity_type'], $_external_link['entity_id'])) {
                            $_item['errors'][] = _w('External link must have all parameters specified: app_id, entity_type and entity_id');
                        } elseif (!is_string($_external_link['app_id'])) {
                            $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'app_id');
                        } elseif (!is_string($_external_link['entity_type'])) {
                            $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'entity_type');
                        } elseif (!is_string($_external_link['entity_id'])) {
                            $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'entity_id');
                        }
                        if (isset($_external_link['entity_data']) && !is_string($_external_link['entity_data'])) {
                            $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'entity_data');
                        }
                    }
                } else {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'external_links');
                }
            }

            if (empty($_item['errors'])) {
                $_item['calc_priority'] = $this->getCalcPriority($_item);
            } else {
                $_item['success'] = false;
                $_item['attachments'] = [];
            }
        }

        $items_ok = array_filter($items, function ($i) {
            return $i['success'];
        });
        $items_err = array_diff_key($items, $items_ok);
        if (!empty($items_ok)) {
            $item_model = pl2()->getModel(pocketlistsItem::class);
            $items_ok = $this->sorting('item', $items_ok);
            try {
                $result = $item_model->multipleInsert($items_ok);
                if ($result->getResult()) {
                    $attachments_log = [];
                    $last_id = $result->lastInsertId();
                    $rows_count = $result->affectedRows();
                    if ($rows_count === count($items_ok)) {
                        $tags = [];
                        $links = [];
                        $favorites = [];
                        foreach ($items_ok as &$_item) {
                            $_item['id'] = $last_id++;
                            $_item['archived'] = ifset($lists, $_item['list_id'], 'archived', 0);
                            $_item['extended_data'] = [
                                'comments_count' => 0
                            ];
                            if (!empty($_item['attachments'])) {
                                $_item['attachments'] = $this->updateFiles($_item['id'], $_item['attachments']);
                                $attachments_log = array_merge($attachments_log, $_item['attachments']);
                            }
                            if ($_item['assigned_contact_id']) {
                                $sender = new pocketlistsNotificationsSender($_item, 'new');
                                $sender->send();
                            }
                            if ($_item['favorite']) {
                                $favorites[] = [
                                    'item_id'    => $_item['id'],
                                    'contact_id' => $user_id,
                                ];
                            }
                            if (!empty($_item['tags'])) {
                                $tags[$_item['id']] = $_item['tags'];
                            }
                            if (!empty($_item['external_links'])) {
                                foreach ($_item['external_links'] as $_link) {
                                    $links[] = [
                                        'item_id'     => $_item['id'],
                                        'app'         => ifset($_link, 'app_id', null),
                                        'entity_type' => ifset($_link, 'entity_type', null),
                                        'entity_id'   => ifset($_link, 'entity_id', null),
                                        'entity_data' => ifset($_link, 'entity_data', null),
                                    ];
                                }
                                $_item['external_links'] = [];
                            }

                            if (ifempty($lists, $_item['list_id'], 'private', 0) == 0) {
                                if (isset($_item['assigned_contact_id']) && $_item['assigned_contact_id'] == $user_id) {
                                    $this->systemLogAction(
                                        pocketlistsLogAction::NEW_SELF_ITEM,
                                        [
                                            'item_id' => $_item['id'],
                                            'list_id' => $_item['list_id'],
                                        ]
                                    );
                                } else {
                                    $this->systemLogAction(
                                        pocketlistsLogAction::NEW_ITEM,
                                        [
                                            'item_id' => $_item['id'],
                                            'list_id' => $_item['list_id'],
                                        ]
                                    );
                                }
                            }
                        }
                        unset($_item);

                        if ($favorites) {
                            $uf_model = pl2()->getModel(pocketlistsUserFavorites::class);
                            $uf_model->multipleInsert($favorites, waModel::INSERT_IGNORE);
                        }
                        if ($tags) {
                            $tag_model = pl2()->getModel(pocketlistsItemTags::class);
                            $tag_model->setTags($tags);
                        }
                        if ($links) {
                            //save external_links
                            $link_model = pl2()->getModel(pocketlistsItemLink::class);
                            if ($link_model->multipleInsert($links)) {
                                $links = $this->getLinks(array_column($items_ok, 'id'));
                                foreach ($links as $_item_id => $_link) {
                                    foreach ($items_ok as &$_item) {
                                        if ($_item['id'] == $_item_id) {
                                            $_item['external_links'] = $_link;
                                            break;
                                        }
                                    }
                                }
                            }
                        }

                        if ($list_ids = array_filter(array_unique(array_column($items_ok, 'list_id')))) {
                            pl2()->getModel(pocketlistsItem::class)->updateByField(
                                ['key_list_id' => $list_ids],
                                ['activity_datetime' => date('Y-m-d H:i:s')]
                            );
                        }

                        if ($location_ids = array_filter(array_unique(array_column($items_ok, 'location_id')))) {
                            pl2()->getModel(pocketlistsLocation::class)->updateById(
                                $location_ids,
                                ['activity_datetime' => date('Y-m-d H:i:s')]
                            );
                        }

                        (new pocketlistsNotificationAboutNewItems())->multiplicityNotify($items_ok);

                        $this->setAnnouncements($items_ok);
                        $this->saveLog(
                            pocketlistsLog::ENTITY_ITEM,
                            pocketlistsLog::ACTION_ADD,
                            $items_ok
                        );
                        if ($attachments_log) {
                            $this->saveLog(
                                pocketlistsLog::ENTITY_ATTACHMENT,
                                pocketlistsLog::ACTION_ADD,
                                $attachments_log
                            );
                        }
                    } else {
                        throw new pocketlistsApiException(_w('Error on transaction'), 400);
                    }
                } else {
                    throw new pocketlistsApiException(_w('Error on transaction'), 400);
                }
            } catch (Exception $ex) {
                throw new pocketlistsApiException(sprintf_wp('Error on transaction import save: %s', $ex->getMessage()), 400);
            }
            pl2()->getCache()->deleteAll();
        }

        $this->response['data'] = $this->responseWrapper(
            array_merge($items_ok, $items_err),
            [
                'id',
                'list_id',
                'contact_id',
                'parent_id',
                'sort',
                'rank',
                'archived',
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
                'note',
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
                'key_list_id',
                'uuid',
                'pro_label_id',
                'tags',
                'attachments',
                'external_links',
                'extended_data'
            ], [
                'id' => 'int',
                'list_id' => 'int',
                'contact_id' => 'int',
                'parent_id' => 'int',
                'sort' => 'int',
                'archived' => 'int',
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
                'key_list_id' => 'int',
                'pro_label_id' => 'int'
            ]
        );
    }
}
