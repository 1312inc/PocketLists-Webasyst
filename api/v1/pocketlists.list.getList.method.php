<?php

class pocketlistsListGetListMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $ids = $this->get('id');
        $starting_from = $this->get('starting_from');
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        if (isset($ids)) {
            if (!is_array($ids)) {
                throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'id'), 400);
            }
            $ids = array_unique(array_filter($ids, function ($_i) {
                return is_numeric($_i) && $_i > 0;
            }));
        }
        if (isset($starting_from)) {
            if (!is_numeric($starting_from)) {
                throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'starting_from'), 400);
            } elseif ($starting_from < 1) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
            $starting_from = date('Y-m-d H:i:s', $starting_from);
        }
        if (isset($limit)) {
            if (!is_numeric($limit)) {
                throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'limit'), 400);
            } elseif ($limit < 1) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
            $limit = (int) min($limit, self::MAX_LIMIT);
        } else {
            $limit = self::DEFAULT_LIMIT;
        }
        if (isset($offset)) {
            if (!is_numeric($offset)) {
                throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'offset'), 400);
            } elseif ($offset < 1) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
            $offset = intval($offset);
        } else {
            $offset = 0;
        }

        $lists = [];
        $total_count = 0;
        $accessed_pockets = pocketlistsRBAC::getAccessPocketForContact(pl2()->getUser()->getId());
        if (!empty($accessed_pockets)) {
            $condition = '1 = 1';
            if ($ids) {
                $condition .= ' AND pl.id IN (i:ids)';
            }
            if ($starting_from) {
                $condition .= " AND update_datetime >= s:starting_from";
            }

            /** @var pocketlistsPocketModel $pocket_model */
            $pocket_model = pl2()->getModel(pocketlistsPocket::class);
            $lists = $pocket_model->query("
                SELECT SQL_CALC_FOUND_ROWS pl.*, pli.contact_id, pli.parent_id, pli.has_children, pli.status, pli.priority, pli.calc_priority, pli.create_datetime, pli.update_datetime, pli.complete_datetime, pli.complete_contact_id, pli.name, pli.due_date, pli.due_datetime, pli.location_id, pli.amount, pli.currency_iso3, pli.assigned_contact_id, pli.repeat, pli.key_list_id, pli.uuid FROM pocketlists_list pl
                LEFT JOIN pocketlists_item pli ON pli.id = pl.key_item_id
                WHERE pl.pocket_id IN (i:pocket_ids) AND $condition AND pl.archived = 0
                ORDER BY pl.sort, pl.rank, pli.update_datetime DESC, pli.create_datetime DESC
                LIMIT i:offset, i:limit
            ", [
                'ids'           => $ids,
                'pocket_ids'    => $accessed_pockets,
                'starting_from' => $starting_from,
                'offset'        => $offset,
                'limit'         => $limit
            ])->fetchAll();
            $total_count = (int) $pocket_model->query('SELECT FOUND_ROWS()')->fetchField();
        }

        $this->response = [
            'offset' => $offset,
            'limit'  => $limit,
            'count'  => $total_count,
            'data'   => $this->filterFields(
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
                    'location_id',
                    'amount',
                    'currency_iso3',
                    'assigned_contact_id',
                    'repeat',
                    'uuid',
                    'pocket_id',
                    'type',
                    'icon',
                    'archived',
                    'hash',
                    'color',
                    'passcode',
                    'key_item_id',
                ],
                [
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
                    'location_id' => 'int',
                    'amount' => 'float',
                    'assigned_contact_id' => 'int',
                    'repeat' => 'int',
                    'pocket_id' => 'int',
                    'archived' => 'int',
                    'key_item_id' => 'int'
                ]
            )
        ];
    }
}
