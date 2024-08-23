<?php

class pocketlistsPocketGetListMethod extends pocketlistsApiAbstractMethod
{
    public function execute()
    {
        $ids = $this->get('id');

        if (isset($ids)) {
            if (!is_array($ids)) {
                throw new waAPIException('error_type', sprintf_wp('Invalid type %s', 'id'), 400);
            }
            $ids = array_unique(array_filter($ids, function ($_i) {
                return is_numeric($_i) && $_i > 0;
            }));
        }

        $plp = pl2()->getModel(pocketlistsPocket::class);
        if ($ids) {
            $pockets = $plp->getByField('id', $ids, true);
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
                    'rank',
                    'name',
                    'color',
                    'passcode',
                    'uuid'
                ],
                [
                    'id' => 'int',
                    'sort' => 'int'
                ]
            )
        ];
    }
}
