<?php

class pocketlistsLogGetMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $starting_from = $this->get('starting_from');
        $entity_type = $this->get('entity_type');
        $entity_id = $this->get('entity_id');
        $offset = $this->get('offset');
        $limit = $this->get('limit');

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

        if (isset($entity_type)) {
            if (!is_string($entity_type)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'entity_type'), 400);
            } elseif (!in_array($entity_type, pocketlistsLogService::ENTITIES)) {
                throw new pocketlistsApiException(_w('Unknown value entity_type'), 400);
            }
        }

        if (isset($entity_id)) {
            if (!is_numeric($entity_id)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'entity_id'), 400);
            } elseif ($entity_id < 1) {
                throw new pocketlistsApiException(_w('The parameter has a negative value'), 400);
            }
        }

        if (isset($limit)) {
            if (!is_numeric($limit)) {
                throw new pocketlistsApiException(sprintf_wp('Type error parameter: “%s”.', 'limit'), 400);
            } elseif ($limit < 1) {
                throw new pocketlistsApiException(_w('The parameter has a negative value'), 400);
            }
            $limit = (int) min($limit, self::MAX_LIMIT);
        } else {
            $limit = self::DEFAULT_LIMIT;
        }
        if (isset($offset)) {
            if (!is_numeric($offset)) {
                throw new pocketlistsApiException(sprintf_wp('Type error parameter: “%s”.', 'offset'), 400);
            } elseif ($offset < 0) {
                throw new pocketlistsApiException(_w('The parameter has a negative value'), 400);
            }
            $offset = (int) $offset;
        } else {
            $offset = 0;
        }

        /** @var pocketlistsLogModel $log_model */
        $log_model = pl2()->getModel(pocketlistsLog::class);
        $query_components = $log_model->getQueryComponents();
        if (isset($entity_type)) {
            $query_components['where']['and'][] = 'l.entity_type = s:entity_type';
            if (isset($entity_id)) {
                $query_components['where']['and'][] = "l.{$entity_type}_id = i:entity_id";
            }
        } elseif (isset($entity_id)) {
            $query_components['where']['and'][] = implode(' OR ', [
                'l.pocket_id = i:entity_id',
                'l.list_id = i:entity_id',
                'l.item_id = i:entity_id',
                'l.attachment_id = i:entity_id',
                'l.comment_id = i:entity_id',
                'l.location_id = i:entity_id'
            ]);
        }
        if (isset($starting_from)) {
            $query_components['where']['and'][] = 'l.create_datetime >= s:starting_from';
        }
        $logs = $log_model->query(
            $log_model->buildSqlComponents($query_components, $limit, $offset, true),
            [
                'entity_type'   => $entity_type,
                'entity_id'     => $entity_id,
                'starting_from' => $starting_from
            ]
        )->fetchAll();
        $total_count = (int) $log_model->query('SELECT FOUND_ROWS()')->fetchField();

        $this->response['meta'] = [
            'offset' => $offset,
            'limit'  => $limit,
            'count'  => $total_count
        ];
        $this->response['data'] = $this->responseListWrapper(
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
            ], [
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
        );
    }
}
