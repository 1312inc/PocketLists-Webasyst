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
        $assigned_contact_ids = array_unique(array_column($items, 'assigned_contact_id'));
        $attachments = array_column($items, 'attachments');

        if (empty($item_ids)) {
            throw new pocketlistsApiException(_w('Items not found'), 404);
        }

        /** @var pocketlistsItemModel $item_model */
        $item_model = pl2()->getModel(pocketlistsItem::class);
        $items_in_db = $item_model->select('*')->where('id IN (:item_ids) AND key_list_id IS NULL', ['item_ids' => $item_ids])->fetchAll('id');
        $list_id_available = pocketlistsRBAC::getAccessListForContact($this->getUser()->getId());
        if (!empty($list_ids)) {
            /** @var pocketlistsListModel $list_model */
            $list_model = pl2()->getModel(pocketlistsList::class);
            $list_ids = $list_model->select('id')->where('id IN (:list_ids)', ['list_ids' => $list_ids])->fetchAll(null, true);
        }
        if (!empty($location_ids)) {
            /** @var pocketlistsLocationModel $location_model */
            $location_model = pl2()->getModel(pocketlistsLocation::class);
            $location_ids = $location_model->select('id')
                ->where('id IN (:list_ids)', ['list_ids' => $location_ids])
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
                $attachments_in_db[$_attachment['item_id']][] = $this->singleFilterFields(
                    pocketlistsAttachment::setUrl($_attachment),
                    ['id', 'item_id', 'filename', 'filetype', 'upload_datetime', 'uuid', 'download_url', 'preview_url'],
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
                $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'id');
            }

            if (isset($_item['list_id'])) {
                if (!is_numeric($_item['list_id'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'list_id');
                } elseif (!in_array($_item['list_id'], $list_ids)) {
                    $_item['errors'][] = _w('List not found');
                } elseif (!in_array($_item['list_id'], $list_id_available)) {
                    $_item['errors'][] = _w('List access denied');
                }
            }

            if (isset($_item['status'])) {
                if (!is_numeric($_item['status'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'status');
                } elseif (!in_array($_item['status'], [pocketlistsItem::STATUS_DONE, pocketlistsItem::STATUS_UNDONE])) {
                    $_item['errors'][] = _w('Unknown value status');
                }
            }

            if (isset($_item['assigned_contact_id'])) {
                if (!is_numeric($_item['assigned_contact_id'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'assigned_contact_id');
                } elseif (!array_key_exists($_item['assigned_contact_id'], $assign_contacts)) {
                    $_item['errors'][] = _w('Assigned contact not found');
                }
            }

            if (isset($_item['priority'])) {
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

            if (isset($_item['due_datetime'])) {
                if (!is_string($_item['due_datetime'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'due_datetime');
                } else {
                    if ($dt = $this->convertDatetimeToServer($_item['due_datetime'])) {
                        $_item['due_date'] = date('Y-m-d', strtotime($dt));
                        $_item['due_datetime'] = $dt;
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
                        $_item['due_datetime'] = '';
                    } else {
                        $_item['errors'][] = _w('Unknown value due_date');
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

            if (isset($_item['location_id'])) {
                if (!is_numeric($_item['location_id'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'location_id');
                } elseif ($_item['location_id'] < 1 || !in_array($_item['location_id'], $location_ids)) {
                    $_item['errors'][] = _w('Location not found');
                }
            }

            if (isset($_item['tags'])) {
                if (!is_array($_item['tags'])) {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'tags');
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

            if (!empty($_item['external_links'])) {
                if (is_array($_item['external_links'])) {
                    foreach ($_item['external_links'] as $_external_link) {
                        if (!isset($_external_link['app_id'], $_external_link['entity_type'], $_external_link['entity_id'])) {
                            $_item['errors'][] = _w('External link must have all parameters specified: app_id, entity_type and entity_id');
                        } elseif (!is_string($_external_link['app_id'])) {
                            $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'app_id');
                        } elseif (!is_string($_external_link['entity_type'])) {
                            $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'entity_type');
                        } elseif (!is_string($_external_link['entity_id'])) {
                            $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'entity_id');
                        }
                        if (isset($_external_link['entity_data']) && !is_string($_external_link['entity_data'])) {
                            $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'entity_data');
                        }
                    }
                } else {
                    $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'external_links');
                }
            }

            if (empty($_item['errors'])) {
                if (
                    isset($_item['status'])
                    && $_item['status'] === pocketlistsItem::STATUS_DONE
                    && $items_in_db[$item_id]['status'] == pocketlistsItem::STATUS_UNDONE
                ) {
                    $_item['complete_datetime'] = date('Y-m-d H:i:s');
                }

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
                        'priority',
                        'location_id',
                        'due_date',
                        'due_datetime',
                        'client_touch_datetime'
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
                if (
                    isset($_item['status'])
                    && $_item['status'] === pocketlistsItem::STATUS_UNDONE
                    && $_item['status'] != $items_in_db[$item_id]['status']
                ) {
                    $_item['move']['item_id'] = $item_id;
                    $_item['move']['prev_status'] = pocketlistsItem::STATUS_UNDONE;
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
            $attachments_log = [];
            $item_model = pl2()->getModel(pocketlistsItem::class);
            $items_ok = $this->sorting('item', $items_ok);
            try {
                foreach ($items_ok as &$_item_ok) {
                    $result = $item_model->updateById($_item_ok['id'], $_item_ok);
                    if ($result) {
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
                        if (!empty($_item['external_links'])) {
                            foreach ($_item['external_links'] as $_link) {
                                $links[] = [
                                    'item_id'     => $_item_ok['id'],
                                    'app'         => ifset($_link, 'app_id', null),
                                    'entity_type' => ifset($_link, 'entity_type', null),
                                    'entity_id'   => ifset($_link, 'entity_id', null),
                                    'entity_data' => ifset($_link, 'entity_data', null),
                                ];
                            }
                        }
                    } else {
                        $_item_ok['success'] = false;
                        $_item_ok['errors'][] = _w('Failed to update');
                    }
                }
                unset($_item_ok);

                if ($tags) {
                    $tag_model = pl2()->getModel(pocketlistsItemTags::class);
                    $tag_model->setTags($tags);
                }
                if ($links) {
                    //save external_links
                    $link_model = pl2()->getModel(pocketlistsItemLink::class);
                    $link_model->setLinks($links);
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

                $items_move = array_column($items_ok, 'move');
                if ($items_move) {
                    pl2()->getModel(pocketlistsItemMove::class)->multipleInsert($items_move);
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
                'repeat',
                'key_list_id',
                'uuid',
                'tags',
                'attachments',
                'external_links'
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
                'activity_datetime' => 'datetime',
                'complete_datetime' => 'datetime',
                'complete_contact_id' => 'int',
                'due_datetime' => 'datetime',
                'client_touch_datetime' => 'dateiso',
                'location_id' => 'int',
                'amount' => 'float',
                'assigned_contact_id' => 'int',
                'repeat' => 'int',
                'key_list_id' => 'int'
        ]);
    }
}
