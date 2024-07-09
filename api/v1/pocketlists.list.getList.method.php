<?php

class pocketlistsListGetListMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $lists = [];
        $id = $this->get('id');
        $starting_from = $this->get('starting_from');
        $limit = $this->get('limit');
        $offset = $this->get('offset');

        if (isset($id)) {
            if (!is_numeric($id)) {
                throw new waAPIException('unknown_value', _w('Unknown value'), 400);
            } elseif ($id < 1) {
                throw new waAPIException('not_found', _w('List not found'), 404);
            }
        }
        if (isset($starting_from)) {
            if (!is_numeric($starting_from)) {
                throw new waAPIException('unknown_value', _w('Unknown value'), 400);
            } elseif ($starting_from < 1) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
            $starting_from = date('Y-m-d H:i:s', $starting_from);
        }
        if (isset($limit)) {
            if (!is_numeric($limit)) {
                throw new waAPIException('unknown_value', _w('Unknown value'), 400);
            } elseif ($limit < 1) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
            $limit = min($limit, self::MAX_LIMIT);
        } else {
            $limit = self::DEFAULT_LIMIT;
        }
        if (isset($offset)) {
            if (!is_numeric($offset)) {
                throw new waAPIException('unknown_value', _w('Unknown value'), 400);
            } elseif ($offset < 1) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
        } else {
            $offset = 0;
        }

        $accessed_pockets = pocketlistsRBAC::getAccessPocketForContact(pl2()->getUser()->getId());
        if (!empty($accessed_pockets)) {
            $condition = '1 = 1';
            if ($id) {
                $condition .= ' AND pl.id = i:id';
            }
            if ($starting_from) {
                $condition .= " AND update_datetime >= s:starting_from";
            }

            /** @var pocketlistsPocketModel $pocket_model */
            $pocket_model = pl2()->getModel(pocketlistsPocket::class);
            $lists = $pocket_model->query("
                SELECT pl.id, pli.name, pli.contact_id, pl.pocket_id, pl.type, pl.icon, pl.color, pl.sort, pli.create_datetime, pli.update_datetime FROM pocketlists_list pl
                LEFT JOIN pocketlists_item pli ON pli.id = pl.key_item_id
                WHERE pl.pocket_id IN (i:pocket_ids) AND $condition AND pl.archived = 0
                ORDER BY pl.sort, pli.update_datetime DESC, pli.create_datetime DESC
                LIMIT i:offset, i:limit
            ", [
                'id' => (int) $id,
                'pocket_ids' => $accessed_pockets,
                'starting_from' => $starting_from,
                'offset' => (int) $offset,
                'limit'  => (int) $limit
            ])->fetchAll();
        }

        $this->response = $this->filterFields(
            $lists,
            [
                'id',
                'contact_id',
                'pocket_id',
                'sort',
                'create_datetime',
                'update_datetime'
            ],
            [
                'id' => 'int',
                'contact_id' => 'int',
                'pocket_id' => 'int',
                'sort' => 'int',
                'create_datetime' => 'datetime',
                'update_datetime' => 'datetime'
            ]
        );
    }
}
