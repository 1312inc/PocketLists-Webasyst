<?php

class pocketlistsListsGetMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $ids = $this->get('id');
        $starting_from = $this->get('starting_from');
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        if (isset($ids)) {
            if (!is_array($ids)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'id'), 400);
            }
            $ids = array_unique(array_filter($ids, function ($_i) {
                return is_numeric($_i) && $_i > 0;
            }));
        }
        if (isset($starting_from)) {
            if (!is_string($starting_from)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'starting_from'), 400);
            } else {
                $dt = date_create($starting_from, new DateTimeZone('UTC'));
                if ($dt) {
                    $dt->setTimezone(new DateTimeZone(date_default_timezone_get()));
                    $starting_from = $dt->format('Y-m-d H:i:s');
                } else {
                    throw new pocketlistsApiException(_w('Unknown value starting_from'), 400);
                }
            }
        }
        if (isset($limit)) {
            if (!is_numeric($limit)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'limit'), 400);
            } elseif ($limit < 1) {
                throw new pocketlistsApiException(_w('The parameter has a negative value'), 400);
            }
            $limit = (int) min($limit, self::MAX_LIMIT);
        } else {
            $limit = self::DEFAULT_LIMIT;
        }
        if (isset($offset)) {
            if (!is_numeric($offset)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'offset'), 400);
            } elseif ($offset < 0) {
                throw new pocketlistsApiException(_w('The parameter has a negative value'), 400);
            }
            $offset = intval($offset);
        } else {
            $offset = 0;
        }

        $lists = [];
        $total_count = 0;
        $accessed_lists = pocketlistsRBAC::getAccessListForContact($this->getUser()->getId());
        if (!empty($accessed_lists)) {
            /** @var pocketlistsListModel $list_model */
            $list_model = pl2()->getModel(pocketlistsList::class);
            $sql_parts = $list_model->getQueryComponents(true);
            $sql_parts['select'] = array_slice($sql_parts['select'], 0, 2);
            $sql_parts['where']['and'] = [
                'l.id IN (i:ids)',
                'l.archived = 0'
            ];
            if ($ids) {
                $ids = array_intersect($ids, $accessed_lists);
            } else {
                $ids = $accessed_lists;
            }
            if ($starting_from) {
                $sql_parts['where']['and'][] = 'i.update_datetime >= s:starting_from OR i.create_datetime >= s:starting_from OR i.activity_datetime >= s:starting_from';
                $sql_parts['order by'] = ['i.update_datetime DESC'];
            } else {
                $sql_parts['order by'] = ['l.sort, l.rank, i.id DESC'];
            }
            if (!empty($ids)) {
                $sql = $list_model->buildSqlComponents($sql_parts);
                $lists = $list_model->query(
                    "$sql LIMIT i:offset, i:limit", [
                    'ids'           => $ids,
                    'starting_from' => $starting_from,
                    'limit'         => $limit,
                    'offset'        => $offset
                ])->fetchAll('id');
                $total_count = (int) $list_model->query('SELECT FOUND_ROWS()')->fetchField();

                if ($lists) {
                    $counters = [];
                    $static_url = wa()->getAppStaticUrl(null, true).'img/listicons/';
                    $summary = $list_model->query(
                        $list_model->buildSqlComponents([
                            'select'   => ['*' => 'i.list_id, IF(i.calc_priority > i.priority, i.calc_priority, i.priority) AS max_priority, COUNT(i2.id) AS started_count, COUNT(i3.id) AS completed_count'],
                            'from'     => ['i' => 'pocketlists_item i'],
                            'join'     => [
                                'LEFT JOIN pocketlists_item i2 ON i2.id = i.id AND i2.status = 0',
                                'LEFT JOIN pocketlists_item i3 ON i3.id = i.id AND i3.status != 0'
                            ],
                            'where'    => ['and' => ['i.list_id IN (i:ids)']],
                            'group by' => ['i.list_id, i.priority, i.calc_priority'],
                            'order by' => ['i.list_id']
                        ]), ['ids' => $ids]
                    )->fetchAll();
                    if ($summary) {
                        foreach ($summary as $_sum) {
                            if ($_sum['started_count']) {
                                $counters['started'][$_sum['list_id']][] = $_sum['started_count'];
                                $counters[$_sum['list_id']][$_sum['max_priority']][] = $_sum['started_count'];
                            }
                            if ($_sum['completed_count']) {
                                $counters['completed'][$_sum['list_id']][] = $_sum['completed_count'];
                            }
                        }
                        unset($summary, $_sum);
                    }

                    if ($total_count === 1 && count($ids) === 1) {
                        $contact_ids = pocketlistsRBAC::getAccessContacts(reset($lists));
                        list($users_access) = $this->getTeammates($contact_ids, 0, self::MAX_LIMIT);
                        $lists[reset($ids)]['users_access'] = $users_access;
                    }
                    foreach ($lists as &$_list) {
                        $max_priority = max(array_keys(ifset($counters, $_list['id'], [pocketlistsItem::PRIORITY_NORM])));
                        $items_priority_count = ($max_priority ? array_sum(ifset($counters, $_list['id'], $max_priority, [])) : pocketlistsItem::PRIORITY_NORM);
                        $_list['icon_url'] = $static_url.$_list['icon'];
                        $_list['extended_data'] = [
                            'items_count'           => array_sum(ifset($counters, 'started', $_list['id'], [])),
                            'items_priority_count'  => $items_priority_count,
                            'items_priority_value'  => $max_priority,
                            'items_completed_count' => array_sum(ifset($counters, 'completed', $_list['id'], [])),
                            'users'                 => ifset($_list, 'users_access', [])
                        ];
                    }
                    unset($_list);
                }
            }
        }

        $this->response['meta'] = [
            'offset' => $offset,
            'limit'  => $limit,
            'count'  => $total_count
        ];
        $this->response['data'] = $this->responseListWrapper(
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
                'repeat',
                'uuid',
                'pocket_id',
                'type',
                'icon',
                'icon_url',
                'archived',
                'hash',
                'color',
                'passcode',
                'key_item_id',
                'items',
                'extended_data'
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
                'activity_datetime' => 'datetime',
                'complete_datetime' => 'datetime',
                'complete_contact_id' => 'int',
                'due_datetime' => 'datetime',
                'client_touch_datetime' => 'dateiso',
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
