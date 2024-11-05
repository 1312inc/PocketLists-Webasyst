<?php

class pocketlistsPocketGetListMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $ids = $this->get('id');

        $plp = pl2()->getModel(pocketlistsPocket::class);
        if (isset($ids)) {
            if (!is_array($ids)) {
                $this->http_status_code = 400;
                $this->response = [
                    'status_code' => 'error',
                    'error'       => sprintf_wp('Invalid type %s', 'id'),
                    'data'        => []
                ];
                return;
            }
            $ids = array_unique(array_filter($ids, function ($_i) {
                return is_numeric($_i);
            }));

            if (!$ids || !$pockets = $plp->getByField('id', $ids, true)) {
                $this->http_status_code = 404;
                $this->response = [
                    'status_code' => 'error',
                    'error'       => _w('Pockets not found'),
                    'data'        => []
                ];
                return;
            }
        } else {
            $pockets = $plp->getAllPockets($this->getUser()->getId());;
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
                'passcode',
                'uuid'
            ], [
                'id' => 'int',
                'sort' => 'int'
            ]
        );
    }
}
