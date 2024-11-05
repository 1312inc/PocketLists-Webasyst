<?php

class pocketlistsListsDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $data = $this->get('id');

        if (empty($data)) {
            $this->http_status_code = 400;
            $this->response = [
                'status_code' => 'error',
                'error'       => sprintf_wp('Missing required parameter: “%s”.', 'id'),
                'data'        => []
            ];
            return;
        } elseif (!is_array($data)) {
            $this->http_status_code = 400;
            $this->response = [
                'status_code' => 'error',
                'error'       => sprintf_wp('Invalid type %s', 'id'),
                'data'        => []
            ];
            return;
        }

        $lists = [];
        $list_ids = array_unique(array_filter($data));

        /** @var pocketlistsListFactory $plf */
        $plf = pl2()->getEntityFactory(pocketlistsList::class);
        if (!empty($list_ids)) {
            $lists = $plf->findById($list_ids);
            $list_ids = [];

            /** @var pocketlistsList $l */
            foreach ((array) $lists as $l) {
                $list_ids[] = $l->getId();
            }
        }

        // validate
        foreach ($data as &$_list) {
            /** set default */
            $_list = [
                'id'      => ifempty($_list),
                'success' => null,
                'errors'  => [],
            ];

            if (empty($_list['id'])) {
                $_list['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'id');
            } elseif (!is_numeric($_list['id'])) {
                $_list['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'id');
            } elseif (!in_array($_list['id'], $list_ids)) {
                $_list['errors'][] = _w('List not found');
            }

            if (!empty($_list['errors'])) {
                $_list['success'] = false;
            }
        }

        $lists_ok = array_filter($data, function ($c) {
            return is_null($c['success']);
        });
        $lists_err = array_diff_key($data, $lists_ok);
        if (!empty($lists_ok)) {
            $logs = [];
            foreach ($lists as $list) {
                try {
                    $id = $list->getId();
                    if ($plf->delete($list)) {
                        $success = true;
                        $logs[] = [
                            'id'        => $id,
                            'pocket_id' => $list->getPocketId(),
                            'name'      => $list->getName()
                        ];
                    } else {
                        $success = false;
                    }
                    foreach ($lists_ok as &$_list_ok) {
                        if ($_list_ok['id'] == $id) {
                            $_list_ok['success'] = $success;
                            break;
                        }
                    }
                } catch (waException $we) {

                }
            }
            if ($logs) {
                $this->saveLog(
                    pocketlistsLog::ENTITY_LIST,
                    pocketlistsLog::ACTION_DELETE,
                    $logs
                );
            }
        }

        $this->response['data'] = $this->responseWrapper(
            array_merge($lists_ok, $lists_err),
            [
                'id'
            ], [
                'id' => 'int'
            ]
        );
    }
}
