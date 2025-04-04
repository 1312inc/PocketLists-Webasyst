<?php

class pocketlistsItemsGetStreamMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $filter = $this->get('filter', true);
        $starting_from = $this->get('starting_from');
        $status = $this->get('status');
        $limit  = $this->get('limit');
        $offset = $this->get('offset');

        if (isset($starting_from)) {
            if (!is_string($starting_from)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'starting_from'), 400);
            }
            $dt = date_create($starting_from, new DateTimeZone('UTC'));
            if ($dt) {
                $dt->setTimezone(new DateTimeZone(date_default_timezone_get()));
                $starting_from = $dt->format('Y-m-d H:i:s');
            } else {
                throw new pocketlistsApiException(_w('Unknown value starting_from'), 400);
            }
        }
        if (isset($status)) {
            if (!is_numeric($status)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'status'), 400);
            } elseif (!in_array($status, [pocketlistsItem::STATUS_UNDONE, pocketlistsItem::STATUS_DONE])) {
                throw new pocketlistsApiException(_w('Unknown value status'), 400);
            }
            $status = (int) $status;
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

        if (!is_string($filter)) {
            throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'filter'), 400);
        }

        $filter = trim(trim($filter), '/');
        if ($filter === '') {
            throw new pocketlistsApiException(sprintf_wp('Missing required parameter: “%s”.', 'filter'), 400);
        }

        try {
            list($items, $total_count) = $this->getItems($filter, $starting_from, $status, $limit, $offset);
        } catch (waAPIException $ex) {
            throw new pocketlistsApiException($ex->getMessage(), 400);
        }

        $this->response['meta'] = [
            'offset' => $offset,
            'limit'  => $limit,
            'count'  => $total_count
        ];
        $this->response['data'] = $this->responseListWrapper(
            $items,
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
                'favorite',
                'repeat',
                'key_list_id',
                'uuid',
                'attachments',
                'extended_data'
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
                'favorite' => 'int',
                'repeat' => 'int',
                'key_list_id' => 'int'
            ]
        );
    }

    private function getItems($filter, $starting_from, $status, $limit, $offset)
    {
        $available_filters = [
            'upnext',
            'due',
            'priority',
            'favorites',
            'user',
            'tag',
            'search',
            'nearby'
        ];
        $filter_split = explode('/', $filter);
        if (
            !in_array(count($filter_split), [1, 2])
            || !in_array($filter_split[0], $available_filters)
        ) {
            throw new pocketlistsApiException(_w('Unknown filter value'));
        }

        $available_list_ids = pocketlistsRBAC::getAccessListForContact(pl2()->getUser()->getId());
        $plim  = pl2()->getModel(pocketlistsItem::class);
        $sql_parts = $plim->getQueryComponents(true);
        $sql_parts['where']['and'][] = 'i.key_list_id IS NULL';
        $sql_parts['where']['and']['def'] = 'i.list_id IN (i:list_ids) OR (i.list_id IS NULL AND (i.contact_id = i:contact_id OR i.assigned_contact_id = i:contact_id))';
        switch ($filter_split[0]) {
            case 'upnext':
                /** upnext */
                if (!empty($filter_split[1])) {
                    throw new pocketlistsApiException(_w('Unknown filter value'));
                }
                $sql_parts['where']['and'][] = 'i.due_date IS NOT NULL';
                $sql_parts['order by'][] = 'i.calc_priority DESC, i.due_date, i.due_datetime ASC';
                break;
            case 'due':
                /** due, due/YYYY-MM-DD, due/YYYY-MM-DD,YYYY-MM-DD */
                if (empty($filter_split[1])) {
                    $sql_parts['where']['and'][] = 'i.due_date IS NOT NULL';
                } else {
                    $pattern = '#^\s?\d{4}-\d{2}-\d{2}$#';
                    $d_data = explode(',', $filter_split[1]);
                    if (!in_array(count($d_data), [1, 2])) {
                        throw new pocketlistsApiException(_w('Unknown filter value'));
                    }
                    if (preg_match($pattern, $d_data[0])) {
                        $dt = date_create($d_data[0]);
                        if ($dt) {
                            $begin_date = $dt->format('Y-m-d');
                            $sql_parts['where']['and'][] = "i.due_date >= '$begin_date'";
                        } else {
                            throw new pocketlistsApiException(_w('Incorrect date'));
                        }
                    } else {
                        throw new pocketlistsApiException(_w('Incorrect date'));
                    }

                    if (isset($d_data[1])) {
                        if (preg_match($pattern, $d_data[1])) {
                            $dt = date_create($d_data[1]);
                            if ($dt) {
                                $end_date = $dt->format('Y-m-d');
                                $sql_parts['where']['and'][] = "i.due_date <= '$end_date'";
                            } else {
                                throw new pocketlistsApiException(_w('Incorrect date'));
                            }
                        } else {
                            throw new pocketlistsApiException(_w('Incorrect date'));
                        }
                    }
                }
                $sql_parts['order by'][] = 'i.due_date, i.due_datetime ASC';
                break;
            case 'priority':
                /** priority */
                if (!empty($filter_split[1])) {
                    throw new pocketlistsApiException(_w('Unknown filter value'));
                }
                $sql_parts['order by'][] = 'i.priority DESC';
                break;
            case 'favorites':
                /** favorites */
                $sql_parts['where']['and']['def'] = 'uf.item_id IS NOT NULL';
                break;
            case 'user':
                /** user/ID */
                if (
                    !isset($filter_split[1])
                    || !is_numeric($filter_split[1])
                    || $filter_split[1] < 1
                    || $filter_split[1] != (int) $filter_split[1]
                ) {
                    throw new pocketlistsApiException(_w('Unknown user'));
                }
                $sql_parts['where']['and'][] = 'i.assigned_contact_id = '.(int) $filter_split[1];
                break;
            case 'tag':
                /** tag/TAG */
                if (!isset($filter_split[1])) {
                    throw new pocketlistsApiException(_w('Empty filter value'));
                }
                $sql_parts['join']['pit'] = 'LEFT JOIN pocketlists_item_tags pit ON pit.item_id = i.id';
                $sql_parts['join']['pt'] = 'LEFT JOIN pocketlists_tag pt ON pt.id = pit.tag_id';
                $sql_parts['where']['and'][] = "pt.`text` = '".$plim->escape($filter_split[1])."'";
                $sql_parts['order by'][] = 'i.calc_priority DESC, i.due_date, i.due_datetime ASC';
                break;
            case 'search':
                /** search/KEYWORD */
                if (!isset($filter_split[1])) {
                    throw new pocketlistsApiException(_w('Empty filter value'));
                }
                $sql_parts['where']['and'][] = "i.name LIKE '%".$plim->escape($filter_split[1])."%'";
                break;
            case 'nearby':
                /** nearby or nearby/28.635896,-106.075763 */
                if (empty($filter_split[1])) {
                    $sql_parts['where']['and'][] = 'i.location_id IS NOT NULL';
                } else {
                    $locations = explode(',', $filter_split[1]);
                    if (count($locations) !== 2) {
                        throw new pocketlistsApiException(_w('Not two values'));
                    }
                    list($latitude, $longitude) = $locations;
                    if (!is_numeric($latitude) || !is_numeric($longitude)) {
                        throw new pocketlistsApiException(_w('Type error filter value'));
                    }
                    $radius_earth = 6371000;
                    $sql_parts['select']['pl.*'] = 'CEILING(SQRT(POW(PI()*('.$plim->escape($latitude).'-pl.location_latitude)/180, 2)+POW(PI()*('.$plim->escape($longitude)."-pl.location_longitude)/180, 2))*$radius_earth) AS meter";
                    $sql_parts['join']['pl'] = 'LEFT JOIN pocketlists_location pl ON i.location_id = pl.id AND pl.location_latitude IS NOT NULL AND pl.location_longitude IS NOT NULL';
                    $sql_parts['where']['and'][] = 'pl.location_latitude IS NOT NULL AND pl.location_longitude IS NOT NULL';
                    $sql_parts['order by'][] = 'meter';
                }
                break;
        }
        if (isset($starting_from)) {
            $sql_parts['where']['and'][] = 'i.update_datetime >= s:starting_from OR i.create_datetime >= s:starting_from OR i.activity_datetime >= s:starting_from';
        } else {
            $status = pocketlistsItem::STATUS_UNDONE;
        }
        if (isset($status)) {
            $sql_parts['where']['and'][] = 'i.status = i:status';
        }
        $sql = $plim->buildSqlComponents($sql_parts);
        $items = $plim->query(
            "$sql LIMIT i:offset, i:limit", [
            'starting_from' => $starting_from,
            'status'        => $status,
            'list_ids'      => $available_list_ids ?: null,
            'contact_id'    => $this->getUser()->getId(),
            'limit'         => $limit,
            'offset'        => $offset
        ])->fetchAll('id');

        $total_count = (int) $plim->query('SELECT FOUND_ROWS()')->fetchField();
        foreach ($items as &$_item) {
            $_item += [
                'attachments'    => [],
                'external_links' => [],
                'tags'           => []
            ];
            $_item['extended_data'] = [
                'comments_count' => (int) $_item['comments_count']
            ];
        }
        $attachments = pl2()->getModel(pocketlistsAttachment::class)->getByField('item_id', array_keys($items), true);
        foreach ($attachments as $_attachment) {
            $items[$_attachment['item_id']]['attachments'][] = $this->singleFilterFields(
                pocketlistsAttachment::setUrl($_attachment),
                ['id', 'item_id', 'filename', 'size', 'filetype', 'upload_datetime', 'uuid', 'download_url', 'preview_url'],
                ['id' => 'int', 'size' => 'int', 'item_id' => 'int', 'upload_datetime' => 'datetime']
            );
        }

        return [$items, $total_count];
    }
}
