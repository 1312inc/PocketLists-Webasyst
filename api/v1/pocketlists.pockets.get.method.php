<?php

class pocketlistsPocketsGetMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $ids = $this->get('id');
        $starting_from = $this->get('starting_from');

        $sql_parts = [
            'select' => [
                '*' => ' SQL_CALC_FOUND_ROWS *',
                'lists_count' => '(SELECT count(*) FROM pocketlists_list pl WHERE pl.pocket_id = pp.id) lists_count',
            ],
            'from' => ['pp' => 'pocketlists_pocket pp'],
            'join' => [],
            'where' => [
                'and' => ['pp.id IN (i:access_id)']
            ],
            'order by' => ['sort, `rank`']
        ];

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
            $sql_parts['where']['and'][] = 'update_datetime >= s:starting_from OR create_datetime >= s:starting_from';
            $sql_parts['order by'] = ['update_datetime DESC, sort, `rank`'];
        }

        $accessed_pockets = pocketlistsRBAC::getAccessPocketForContact($this->getUser()->getId());
        if (isset($ids)) {
            if (!is_array($ids)) {
                throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'id'), 400);
            }
            $ids = array_unique(array_filter($ids, function ($_i) {
                return is_numeric($_i);
            }));
            if (!$ids) {
                throw new pocketlistsApiException(_w('Pockets not found'), 404);
            }
            $accessed_pockets = array_intersect($accessed_pockets, $ids);
            if (empty($accessed_pockets)) {
                throw new pocketlistsApiException(_w('Pocket access denied'), 403);
            }
        }

        $plp = pl2()->getModel(pocketlistsPocket::class);
        $sql = $plp->buildSqlComponents($sql_parts);
        $pockets = $plp->query($sql, [
            'access_id' => $accessed_pockets,
            'starting_from' => $starting_from
        ])->fetchAll();

        foreach ($pockets as &$_pocket) {
            $_pocket['extended_data']['lists_count'] = (int) ifset($_pocket, 'lists_count', 0);
        }

        $this->response['meta'] = [
            'offset' => 0,
            'limit'  => self::DEFAULT_LIMIT,
            'count'  => count($pockets),
        ];
        $this->response['data'] = $this->responseListWrapper(
            $pockets,
            [
                'id',
                'sort',
                'rank',
                'name',
                'color',
                'create_datetime',
                'update_datetime',
                'passcode',
                'uuid',
                'extended_data'
            ], [
                'id' => 'int',
                'sort' => 'int',
                'create_datetime' => 'datetime',
                'update_datetime' => 'datetime'
            ]
        );
    }
}
