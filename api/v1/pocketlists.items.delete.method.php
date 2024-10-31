<?php

class pocketlistsItemsDeleteMethod extends pocketlistsApiAbstractMethod
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
                'error'       =>  sprintf_wp('Invalid type %s', 'id'),
                'data'        => []
            ];
            return;
        }

        $items = [];
        $item_ids = array_unique(array_filter($data));

        /** @var pocketlistsItemFactory $plf */
        $plf = pl2()->getEntityFactory(pocketlistsItem::class);

        if (!empty($item_ids)) {
            $items = $plf->findById($item_ids);
            $item_ids = [];

            /** @var pocketlistsItem $i */
            foreach ((array) $items as $i) {
                $item_ids[] = $i->getId();
            }
        }

        // validate
        foreach ($data as &$_item) {
            /** set default */
            $_item = [
                'id'      => ifempty($_item),
                'success' => null,
                'errors'  => [],
            ];

            if (empty($_item['id'])) {
                $_item['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'id');
            } elseif (!is_numeric($_item['id'])) {
                $_item['errors'][] = sprintf_wp('Type error parameter: “%s”.', 'id');
            } elseif (!in_array($_item['id'], $item_ids)) {
                $_item['errors'][] = _w('Item not found');
            }

            if (!empty($_item['errors'])) {
                $_item['success'] = false;
            }
        }

        $items_ok = array_filter($data, function ($c) {
            return is_null($c['success']);
        });
        $items_err = array_diff_key($data, $items_ok);
        if (!empty($items_ok)) {
            $logs = [];
            foreach ($items as $item) {
                try {
                    $id = $item->getId();
                    if ($plf->delete($item)) {
                        $success = true;
                        $logs[] = [
                            'id'      => $item->getId(),
                            'list_id' => $item->getListId(),
                            'name'    => $item->getName()
                        ];
                    } else {
                        $success = false;
                    }
                    foreach ($items_ok as &$_items_ok) {
                        if ($_items_ok['id'] == $id) {
                            $_items_ok['success'] = $success;
                            break;
                        }
                    }
                } catch (waException $we) {

                }
            }
            $this->saveLog(
                pocketlistsLog::ENTITY_ITEM,
                pocketlistsLog::ACTION_DELETE,
                $logs
            );
        }

        $this->response['data'] = $this->responseWrapper(
            array_merge($items_ok, $items_err),
            [
                'id'
            ], [
                'id' => 'int'
            ]
        );
    }
}
