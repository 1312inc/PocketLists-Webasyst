<?php

class pocketlistsLogGetDeletedSummaryMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $starting_from = $this->get('starting_from');

        if (isset($starting_from)) {
            if (!is_string($starting_from)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid data type: “%s”', 'starting_from'), 400);
            } else {
                $dt = date_create($starting_from, new DateTimeZone('UTC'));
                if ($dt) {
                    $dt->setTimezone(new DateTimeZone(date_default_timezone_get()));
                    $starting_from = $dt->format('Y-m-d H:i:s');
                } else {
                    throw new pocketlistsApiException(_w('Invalid value: “starting_from” (must be ISO 8601 datetime)'), 400);
                }
            }
        } else {
            throw new pocketlistsApiException(sprintf_wp('Missing required parameter: “%s”.', 'starting_from'), 400);
        }

        $filters = [];
        $pockets_available = [];
        $lists_available = [];

        /** @var pocketlistsLogModel $log_model */
        $log_model = pl2()->getModel(pocketlistsLog::class);
        $query_components = $log_model->getQueryComponents();
        $query_components['select'] = ['l.entity_type, COUNT(l.entity_type) AS summ'];
        if (!pocketlistsRBAC::isAdmin()) {
            $pockets_available = pocketlistsRBAC::getAccessPocketForContact($this->getUser());
            if ($pockets_available) {
                $filters[] = '(l.entity_type = s:pocket AND l.pocket_id IN (i:pockets_available))';
            }

            $lists_available = pocketlistsRBAC::getAccessListForContact($this->getUser());
            if ($lists_available) {
                $filters[] = '(l.entity_type IN (s:entity_types) AND l.list_id IN (i:lists_available))';
            }

            $filters[] = 'l.assigned_contact_id = i:user_id';
            $query_components['where']['and'][] = implode(' OR ', $filters);
        }

        $query_components['where']['and'][] = 'l.action = s:delete OR (l.action = s:unshare AND l.contact_id = i:user_id)';
        $query_components['where']['and'][] = 'l.create_datetime >= s:starting_from';
        $query_components['group_by'][] = 'l.entity_type';
        $log_summary = $log_model->query(
            $log_model->buildSqlComponents($query_components, self::MAX_LIMIT, 0, true),
            [
                'pockets_available' => $pockets_available,
                'lists_available'   => $lists_available,
                'pocket'            => pocketlistsLog::ENTITY_POCKET,
                'delete'            => pocketlistsLog::ACTION_DELETE,
                'unshare'           => pocketlistsLog::ACTION_UNSHARE,
                'entity_types'      => [pocketlistsLog::ENTITY_LIST, pocketlistsLog::ENTITY_ITEM, pocketlistsLog::ENTITY_COMMENT],
                'user_id'           => $this->getUser()->getId(),
                'starting_from'     => $starting_from
            ]
        )->fetchAll('entity_type', 1);

        $this->response['data'] = [
            'starting_from' => $this->formatDatetimeToISO8601($starting_from),
            'ending_to'     => $this->formatDatetimeToISO8601(date('Y-m-d H:i:s')),
            'pockets'       => (int) ifempty($log_summary, pocketlistsLogContext::POCKET_ENTITY, 0),
            'lists'         => (int) ifempty($log_summary, pocketlistsLogContext::LIST_ENTITY, 0),
            'items'         => (int) ifempty($log_summary, pocketlistsLogContext::ITEM_ENTITY, 0),
            'comments'      => (int) ifempty($log_summary, pocketlistsLogContext::COMMENT_ENTITY, 0),
            'attachments'   => (int) ifempty($log_summary, pocketlistsLogContext::ATTACHMENT_ENTITY, 0),
            'location'      => (int) ifempty($log_summary, pocketlistsLogContext::LOCATION_ENTITY, 0),
            'user'          => (int) ifempty($log_summary, pocketlistsLogContext::USER_ENTITY, 0),
        ];
    }
}
