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
            } elseif ($offset < 1) {
                throw new pocketlistsApiException(_w('The parameter has a negative value'), 400);
            }
            $offset = intval($offset);
        } else {
            $offset = 0;
        }

        $lists = [];
        $total_count = 0;
        $accessed_pockets = pocketlistsRBAC::getAccessPocketForContact(pl2()->getUser()->getId());
        if (!empty($accessed_pockets)) {
            /** @var pocketlistsListModel $list_model */
            $list_model = pl2()->getModel(pocketlistsList::class);
            $sql_parts = $list_model->getQueryComponents(true);
            $sql_parts['select'] = array_slice($sql_parts['select'], 0, 2);
            $sql_parts['where']['and'] = [
                'l.pocket_id IN (i:pocket_ids)',
                'l.archived = 0'
            ];
            if ($ids) {
                $sql_parts['where']['and'][] = 'l.id IN (i:ids)';
            }
            if ($starting_from) {
                $sql_parts['where']['and'][] = 'i.update_datetime >= s:starting_from OR i.create_datetime >= s:starting_from';
                $sql_parts['order by'] = ['i.update_datetime DESC'];
            } else {
                $sql_parts['order by'] = ['l.sort, l.rank, i.id DESC'];
            }
            $sql = $list_model->buildSqlComponents($sql_parts);
            $lists = $list_model->query(
                "$sql LIMIT i:offset, i:limit", [
                'ids'           => $ids,
                'pocket_ids'    => $accessed_pockets,
                'starting_from' => $starting_from,
                'limit'         => $limit,
                'offset'        => $offset
            ])->fetchAll('id');
            $total_count = (int) $list_model->query('SELECT FOUND_ROWS()')->fetchField();

            if ($lists) {
                $priority_count = [];
                $static_url = wa()->getAppStaticUrl(null, true).'img/listicons/';
                $summary = $list_model->query(
                    $list_model->buildSqlComponents([
                        'select'   => ['*' => 'list_id, priority, COUNT(id) AS cnt'],
                        'from'     => ['pi' => 'pocketlists_item'],
                        'join'     => [],
                        'where'    => ['and' => [$ids ? 'list_id IN (i:ids)' : 'list_id IS NOT NULL', 'status = 0']],
                        'group by' => ['list_id, priority'],
                        'order by' => ['list_id, priority']
                    ]), ['ids' => $ids]
                )->fetchAll();
                if ($summary) {
                    foreach ($summary as $_summ) {
                        $priority_count[$_summ['list_id']][$_summ['priority']] = $_summ['cnt'];
                    }
                    unset($summary);
                }
                foreach ($lists as &$_list) {
                    $data = ifset($priority_count, $_list['id'], null);
                    $max_priority = ($data ? max(array_keys($data)) : null);
                    $max_priority = ($max_priority == 0 ? null : $max_priority);
                    $_list['icon_url'] = $static_url.$_list['icon'];
                    $_list['extended_data'] = [
                        'count' => ($data ? array_sum($data) : 0),
                        'priority_count' => (int) ifset($data, $max_priority, 0)
                    ];
                }
                unset($_list);
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
                'complete_datetime' => 'datetime',
                'complete_contact_id' => 'int',
                'due_datetime' => 'datetime',
                'client_touch_datetime' => 'datetime',
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
