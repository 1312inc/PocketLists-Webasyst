<?php

class pocketlistsPocketGetListMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $id = $this->get('id');

        if (isset($id)) {
            if (!is_numeric($id)) {
                throw new waAPIException('unknown_value', _w('Unknown id value'), 400);
            } elseif ($id < 1) {
                throw new waAPIException('not_found', _w('Pocket not found'), 404);
            }
        }

        $plp = pl2()->getModel(pocketlistsPocket::class);
        if ($id) {
            $pockets = [$plp->getById($id)];
        } else {
            $pockets = $plp->getAllPockets($this->getUser()->getId());;
        }

        $this->response = [
            'offset' => 0,
            'limit'  => self::DEFAULT_LIMIT,
            'count'  => count($pockets),
            'data'   => $this->filterFields(
                $pockets,
                [
                    'id',
                    'sort',
                    'name',
                    'color',
                    'passcode'
                ],
                [
                    'id' => 'int'
                ]
            )
        ];
    }
}
