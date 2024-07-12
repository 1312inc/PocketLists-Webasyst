<?php

class pocketlistsLogGetMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $offset = $this->get('offset');
        $limit = $this->get('limit');

        if (isset($limit)) {
            if (!is_numeric($limit)) {
                throw new waAPIException('unknown_value', _w('Unknown value'), 400);
            } elseif ($limit < 1) {
                throw new waAPIException('negative_value', _w('The parameter has a negative value'), 400);
            }
            $limit = (int) min($limit, self::MAX_LIMIT);
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

        /** @var pocketlistsLogModel $log_model */
        $log_model = pl2()->getModel(pocketlistsLog::class);
        $logs = $log_model->getLastAll([], [], false, $offset, $limit, true);
        $total_count = (int) $log_model->query('SELECT FOUND_ROWS()')->fetchField();

        $this->response = [
            'offset' => $offset,
            'limit'  => $limit,
            'count'  => $total_count,
            'data'   => $this->filterFields(
                $logs,
                [
                    'id',
                    'action',
                    'entity_type',
                    'contact_id',
                    'pocket_id',
                    'list_id',
                    'item_id',
                    'comment_id',
                    'attachment_id',
                    'location_id',
                    'additional_id',
                    'assigned_contact_id',
                    'create_datetime',
                    'params'
                ],
                [
                    'id' => 'int',
                    'contact_id' => 'int',
                    'pocket_id' => 'int',
                    'list_id' => 'int',
                    'item_id' => 'int',
                    'comment_id' => 'int',
                    'attachment_id' => 'int',
                    'location_id' => 'int',
                    'additional_id' => 'int',
                    'assigned_contact_id' => 'int',
                    'create_datetime' => 'datetime'
                ]
            )
        ];
    }
}
