<?php

class pocketlistsItemsUpdateMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PATCH;

    public function execute()
    {
        $items = $this->readBodyAsJson();
        if (empty($items)) {
            throw new pocketlistsApiException(_w('Missing `data`'), 400);
        } elseif (!is_array($items)) {
            throw new pocketlistsApiException(_w('Type error data'), 400);
        }

        $assign_contacts = [];
        $attachment_uuids = [];
        $attachments_in_db = [];
        $item_ids = array_unique(array_column($items, 'id'));
        $list_ids = array_unique(array_column($items, 'list_id'));
        $location_ids = array_unique(array_filter(array_column($items, 'location_id')));
        $label_ids = array_unique(array_filter(array_column($items, 'pro_label_id')));
        $assigned_contact_ids = array_unique(array_column($items, 'assigned_contact_id'));
        $attachments = array_column($items, 'attachments');

        if (empty($item_ids)) {
            throw new pocketlistsApiException(_w('Items not found'), 404);
        }

        $current_user_id = $this->getUser()->getId();
        /** @var pocketlistsItemModel $item_model */
        $item_model = pl2()->getModel(pocketlistsItem::class);

        $sql_parts = $item_model->getQueryComponents();
        $sql_parts['where']['and'] = [
            'i.id IN (i:item_ids)',
            'i.key_list_id IS NULL'
        ];
        $items_in_db = $item_model->query($item_model->buildSqlComponents($sql_parts), [
            'item_ids'   => $item_ids,
            'contact_id' => $current_user_id
        ])->fetchAll('id');
        $list_id_available = pocketlistsRBAC::getAccessListForContact($current_user_id);

        /** @var pocketlistsListModel $list_model */
        $list_model = pl2()->getModel(pocketlistsList::class);
        if (!empty($list_ids)) {
            $list_ids = $list_model->select('id')->where('id IN (:list_ids)', ['list_ids' => $list_ids])->fetchAll(null, true);
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
        if (!empty($attachments)) {
            foreach ($attachments as $_attachment) {
                $attachment_uuids = array_merge($attachment_uuids, array_column($_attachment, 'uuid'));
            }
            unset($attachments);
            $attachment_uuids = $this->getEntitiesByUuid('attachment', $attachment_uuids);
            $attachment_uuids = array_keys($attachment_uuids);
        }

        /** @var pocketlistsAttachmentModel $attachment_model */
        $attachment_model = pl2()->getModel(pocketlistsAttachment::class);
        $attachments = $attachment_model->getByField('item_id', $item_ids, true);
        if ($attachments) {
            foreach ($attachments as $_attachment) {
                $_attachment['file_name'] = $_attachment['filename'];
                $attachments_in_db[$_attachment['item_id']][] = $this->singleFilterFields(
                    pocketlistsAttachment::setUrl($_attachment),
                    ['id', 'item_id', 'file_name', 'filetype', 'upload_datetime', 'uuid', 'download_url', 'preview_url'],
                    ['id' => 'int', 'item_id' => 'int', 'upload_datetime' => 'datetime']
                );
            }
            unset($attachments);
        }

        /** validate */
        foreach ($items as &$_item) {
            $item_id = ifset($_item, 'id', null);
            $action = (ifset($_item, 'action', null) === self::ACTIONS[1] ? self::ACTIONS[1] : self::ACTIONS[0]);
            $_item += [
                'update_datetime'   => date('Y-m-d H:i:s'),
                'activity_datetime' => date('Y-m-d H:i:s'),
                'tags'              => $this->tagFilter(ifset($_item, 'tags', null)),
                'attachments'       => ifset($_item, 'attachments', []),
                'external_links'    => ifset($_item, 'external_links', []),
                'success'           => true,
                'errors'            => []
            ];

            if (empty($item_id)) {
                $_item['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'id');
            } elseif (!is_numeric($item_id)) {
                $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'id');
            }

            if (isset($_item['list_id'])) {
                if (!is_numeric($_item['list_id'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'list_id');
                } elseif (!in_array($_item['list_id'], $list_ids)) {
                    $_item['errors'][] = _w('List not found');
                } elseif (!in_array($_item['list_id'], $list_id_available)) {
                    $_item['errors'][] = _w('List access denied');
                }
            }

            if (isset($_item['status'])) {
                if (!is_numeric($_item['status'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'status');
                } elseif (!in_array($_item['status'], [pocketlistsItem::STATUS_DONE, pocketlistsItem::STATUS_UNDONE])) {
                    $_item['errors'][] = _w('Invalid value status');
                }
            }

            if (isset($_item['assigned_contact_id'])) {
                if (!is_numeric($_item['assigned_contact_id'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'assigned_contact_id');
                } elseif (!array_key_exists($_item['assigned_contact_id'], $assign_contacts)) {
                    $_item['errors'][] = _w('Assigned contact not found');
                }
            }

            if (isset($_item['repeat_frequency']) &&!is_numeric($_item['repeat_frequency'])) {
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

            if (isset($_item['favorite'])) {
                if (!is_numeric($_item['favorite'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'favorite');
                } elseif (!in_array($_item['favorite'], [0, 1])) {
                    $_item['errors'][] = _w('Invalid value favorite');
                }
            }

            if (isset($_item['priority'])) {
                if (!is_numeric($_item['priority'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'priority');
                } elseif (!in_array($_item['priority'], [0, 1, 2, 3, 4, 5])) {
                    $_item['errors'][] = _w('Invalid value priority');
                }
            }

            if (isset($_item['name']) && !is_string($_item['name'])) {
                $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'name');
            }

            if (isset($_item['note']) && !is_string($_item['note'])) {
                $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'note');
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
                        $_item['due_datetime'] = '';
                    } else {
                        $_item['errors'][] = _w('Invalid value due_date');
                    }
                }
            }

            if (!array_key_exists($item_id, $items_in_db)) {
                $_item['errors'][] = _w('Item not found');
            }  elseif (
                (isset($items_in_db[$item_id]['list_id']) && !in_array($items_in_db[$item_id]['list_id'], $list_id_available))
                || (isset($_item['list_id']) && !in_array($_item['list_id'], $list_id_available))
            ) {
                $_item['errors'][] = _w('List access denied');
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

            if (isset($_item['pro_label_id'])) {
                if (!is_numeric($_item['pro_label_id'])) {
                    $_item['errors'][] = sprintf_wp('Invalid data type: “%s”', 'pro_label_id');
                } elseif ($_item['pro_label_id'] < 1 || !in_array($_item['pro_label_id'], $label_ids)) {
                    $_item['errors'][] = _w('Label not found');
                }
            }

            if (isset($_item['tags'])) {
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
                if ($action == self::ACTIONS[0]) {
                    // patch
                    $_item += $items_in_db[$item_id];
                    $_item['calc_priority'] = $this->getCalcPriority($_item);
                    if (trim((string) $_item['due_datetime']) === '') {
                        $_item['due_datetime'] = null;
                    }
                    if (trim((string) $_item['due_date']) === '') {
                        $_item['due_date'] = null;
                    }
                    if (isset($_item['prev_item_id'])) {
                        if ($_item['prev_item_id'] === 0) {
                            $_item['prev_item_id'] = null;
                        }
                        $_item['sort'] = null;
                        $_item['rank'] = null;
                    }
                } else {
                    // update
                    $_item += array_fill_keys([
                        'list_id',
                        'name',
                        'note',
                        'sort',
                        'rank',
                        'status',
                        'assigned_contact_id',
                        'repeat_frequency',
                        'repeat_interval',
                        'repeat_occurrence',
                        'priority',
                        'location_id',
                        'due_date',
                        'due_datetime',
                        'client_touch_datetime',
                        'pro_label_id'
                    ], null) + ifset($items_in_db, $item_id, []);
                    $_item['status'] = ifset($_item, 'status', pocketlistsItem::STATUS_UNDONE);
                    $_item['calc_priority'] = $this->getCalcPriority($_item);
                }
                if ($_item['list_id'] != $items_in_db[$item_id]['list_id']) {
                    /* fields pocketlistsItemMoveModel */
                    $_item['move'] = [
                        'item_id'      => $item_id,
                        'prev_list_id' => $items_in_db[$item_id]['list_id']
                    ];
                }

                if (isset($_item['status'])) {
                    if (
                        $_item['status'] === pocketlistsItem::STATUS_DONE
                        && $items_in_db[$item_id]['status'] == pocketlistsItem::STATUS_UNDONE
                    ) {
                        /** status 0 -> 1 */
                        $_item['complete_datetime'] = date('Y-m-d H:i:s');
                        $_item['complete_contact_id'] = $current_user_id;
                    } elseif (
                        $_item['status'] === pocketlistsItem::STATUS_UNDONE
                        && $items_in_db[$item_id]['status'] == pocketlistsItem::STATUS_DONE
                    ) {
                        /** status 1 -> 0 */
                        $_item['complete_datetime'] = null;
                        $_item['complete_contact_id'] = null;
                        $_item['move']['item_id'] = $item_id;
                        $_item['move']['prev_status'] = pocketlistsItem::STATUS_UNDONE;
                    } else {
                        unset($_item['complete_datetime']);
                    }
                }

                unset($_item['errors']);
            } else {
                $_item['success'] = false;
                $_item['attachments'] = [];
            }
        }
        unset($_item, $items_in_db);

        $items_ok = array_filter($items, function ($i) {
            return $i['success'];
        });
        $items_err = array_diff_key($items, $items_ok);
        if (!empty($items_ok)) {
            $tags = [];
            $links = [];
            $set_favorite = [];
            $unset_favorite = [];
            $attachments_log = [];
            try {
                $lists = $list_model->select('id, private, archived')
                    ->where('id IN (:list_ids)', ['list_ids' => array_column($items_ok, 'list_id')])
                    ->fetchAll('id');

                $item_model = pl2()->getModel(pocketlistsItem::class);
                $items_ok = $this->sorting('item', $items_ok);
                foreach ($items_ok as &$_item_ok) {
                    if ($item_model->updateById($_item_ok['id'], $_item_ok)) {
                        $_item_ok['archived'] = ifset($lists, $_item_ok['list_id'], 'archived', 0);
                        if (isset($_item_ok['favorite'])) {
                            if ($_item_ok['favorite']) {
                                $set_favorite[] = [
                                    'item_id'    => $_item_ok['id'],
                                    'contact_id' => $current_user_id
                                ];
                            } else {
                                $unset_favorite[] = ['item_id' => $_item_ok['id']];
                            }
                        }
                        if (isset($_item_ok['tags'])) {
                            $tags[$_item_ok['id']] = $_item_ok['tags'];
                        }
                        if (!empty($_item_ok['attachments'])) {
                            $_item_ok['attachments'] = $this->updateFiles($_item_ok['id'], $_item_ok['attachments']);
                            $attachments_log = array_merge($attachments_log, $_item_ok['attachments']);
                        }
                        if (isset($attachments_in_db[$_item_ok['id']])) {
                            $_item_ok['attachments'] = array_merge($_item_ok['attachments'], $attachments_in_db[$_item_ok['id']]);
                        }
                        if (isset($_item_ok['attachments'])) {
                            $_item_ok['attachments'] = $this->filterFields(
                                $_item_ok['attachments'],
                                ['id', 'item_id', 'file_name', 'ext', 'size', 'upload_datetime', 'uuid', 'download_url', 'preview_url'],
                                ['id' => 'int', 'size' => 'int', 'item_id' => 'int', 'upload_datetime' => 'datetime']
                            );
                        }
                        if (!empty($_item_ok['external_links'])) {
                            foreach ($_item_ok['external_links'] as $_link) {
                                $links[] = [
                                    'item_id'     => $_item_ok['id'],
                                    'app'         => ifset($_link, 'app_id', null),
                                    'entity_type' => ifset($_link, 'entity_type', null),
                                    'entity_id'   => ifset($_link, 'entity_id', null),
                                    'entity_data' => ifset($_link, 'entity_data', null),
                                ];
                            }
                            $_item_ok['external_links'] = [];
                        }
                        if (ifempty($lists, $_item_ok['list_id'], 'private', 0) == 0) {
                            if (!empty($_item_ok['complete_datetime'])) {
                                $this->systemLogAction(pocketlistsLogAction::ITEM_COMPLETED, ['item_id' => $_item_ok['id']]);
                            } elseif (isset($_item_ok['assigned_contact_id'])) {
                                $this->systemLogAction(pocketlistsLogAction::ITEM_ASSIGN, ['item_id' => $_item_ok['id'], 'assigned_to' => $_item_ok['assigned_contact_id']]);
                            }
                        }
                    } else {
                        $_item_ok['success'] = false;
                        $_item_ok['errors'][] = _w('Failed to update');
                    }
                }
                unset($_item_ok);

                if ($set_favorite || $unset_favorite) {
                    $uf_model = pl2()->getModel(pocketlistsUserFavorites::class);
                    if (!empty($set_favorite)) {
                        $uf_model->multipleInsert($set_favorite, waModel::INSERT_IGNORE);
                    }
                    if (!empty($unset_favorite)) {
                        $uf_model->exec("
                            DELETE FROM pocketlists_user_favorites
                            WHERE contact_id = i:contact_id AND item_id IN (i:item_ids)
                        ", [
                            'contact_id' => $current_user_id,
                            'item_ids'   => array_column($unset_favorite, 'item_id')
                        ]);
                    }
                }
                if ($tags) {
                    $tag_model = pl2()->getModel(pocketlistsItemTags::class);
                    $tag_model->setTags($tags);
                }
                if ($links) {
                    pl2()->getModel(pocketlistsItemLink::class)->setLinks($links);
                }

                $links = $this->getLinks(array_column($items_ok, 'id'));
                foreach ($links as $_item_id => $_link) {
                    foreach ($items_ok as &$_item) {
                        if ($_item['id'] == $_item_id) {
                            $_item['external_links'] = $_link;
                            break;
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

                $this->updateAnnouncements($items_ok);
                $this->saveLog(
                    pocketlistsLog::ENTITY_ITEM,
                    pocketlistsLog::ACTION_UPDATE,
                    $items_ok
                );

                if ($items_move = array_column($items_ok, 'move')) {
                    pl2()->getModel(pocketlistsItemMove::class)->multipleInsert($items_move);
                }

                if (array_column($items_ok, 'complete_datetime')) {
                    (new pocketlistsNotificationAboutCompleteItems())->multiplicityNotifyAboutCompleteItems($items_ok);
                }

                if ($attachments_log) {
                    $this->saveLog(
                        pocketlistsLog::ENTITY_ATTACHMENT,
                        pocketlistsLog::ACTION_ADD,
                        $attachments_log
                    );
                }
            } catch (Exception $ex) {
                throw new pocketlistsApiException(sprintf_wp('Error on transaction import save: %s', $ex->getMessage()), 400);
            }
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
                'external_links'
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
