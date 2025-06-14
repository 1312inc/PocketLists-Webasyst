<?php

class pocketlistsPocketsDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $data = $this->get('id');

        if (empty($data)) {
            throw new pocketlistsApiException(sprintf_wp('Missing required parameter: “%s”.', 'id'), 400);
        } elseif (!is_array($data)) {
            throw new pocketlistsApiException(sprintf_wp('Invalid data type: “%s”', 'id'), 400);
        }

        $pockets = [];
        $pocket_ids = array_unique(array_filter($data));

        /** @var pocketlistsPocketFactory $plf */
        $plf = pl2()->getEntityFactory(pocketlistsPocket::class);
        if (!empty($pocket_ids)) {
            $pockets = $plf->findById($pocket_ids);
            $pocket_ids = [];

            /** @var pocketlistsPocket $p */
            foreach ((array) $pockets as $p) {
                $pocket_ids[] = $p->getId();
            }
        }

        // validate
        foreach ($data as &$_pocket) {
            /** set default */
            $_pocket = [
                'id'      => ifempty($_pocket),
                'success' => null,
                'errors'  => []
            ];

            if (empty($_pocket['id'])) {
                $_pocket['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'id');
            } elseif (!is_numeric($_pocket['id'])) {
                $_pocket['errors'][] = sprintf_wp('Invalid data type: “%s”', 'id');
            } elseif (!in_array($_pocket['id'], $pocket_ids)) {
                $_pocket['success'] = true;
            }

            if (!empty($_pocket['errors'])) {
                $_pocket['success'] = false;
            }
        }

        $pockets_ok = array_filter($data, function ($p) {
            return is_null($p['success']);
        });
        $pockets_err = array_diff_key($data, $pockets_ok);
        if (!empty($pockets_ok)) {
            $logs = [];
            foreach ($pockets as $pocket) {
                try {
                    $id = $pocket->getId();
                    if ($plf->delete($pocket)) {
                        $success = true;
                        $logs[] = [
                            'pocket_id' => $id,
                            'name' => $pocket->getName()
                        ];
                    } else {
                        $success = false;
                    }
                    foreach ($pockets_ok as &$_pocket_ok) {
                        if ($_pocket_ok['id'] == $id) {
                            $_pocket_ok['success'] = $success;
                            break;
                        }
                    }
                } catch (waException $we) {

                }
            }
            if ($logs) {
                $this->saveLog(
                    pocketlistsLog::ENTITY_POCKET,
                    pocketlistsLog::ACTION_DELETE,
                    $logs
                );
            }
        }

        $this->response['data'] = $this->responseWrapper(
            array_merge($pockets_ok, $pockets_err),
            [
                'id'
            ], [
                'id' => 'int'
            ]
        );
    }
}
