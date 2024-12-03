<?php

class pocketlistsPocketsGetMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $ids = $this->get('id');
        $starting_from = $this->get('starting_from');

        $where = 'id IN (i:access_id)';
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
        }
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
            $where .= ' AND (update_datetime >= s:starting_from OR create_datetime >= s:starting_from)';
            $order = 'update_datetime DESC, sort, `rank`';
        } else {
            $order = 'sort, `rank`';
        }

        $accessed_pockets = pocketlistsRBAC::getAccessPocketForContact($this->getUser()->getId());
        $plp = pl2()->getModel(pocketlistsPocket::class);
        $pockets = $plp->select('*')
            ->where($where, ['access_id' => $accessed_pockets, 'starting_from' => $starting_from])
            ->order($order)
            ->fetchAll();

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
                'uuid'
            ], [
                'id' => 'int',
                'sort' => 'int',
                'create_datetime' => 'datetime',
                'update_datetime' => 'datetime'
            ]
        );
    }
}
