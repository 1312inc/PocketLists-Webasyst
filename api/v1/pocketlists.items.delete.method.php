<?php

class pocketlistsItemsDeleteMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_DELETE;

    public function execute()
    {
        $data = $this->get('id');

        if (empty($data)) {
            throw new pocketlistsApiException(sprintf_wp('Missing required parameter: “%s”.', 'id'), 400);
        } elseif (!is_array($data)) {
            throw new pocketlistsApiException(sprintf_wp('Invalid type %s', 'id'), 400);
        }

        $items = [];
        $items_in_db = [];

        /** @var pocketlistsItemFactory $plf */
        $plf = pl2()->getEntityFactory(pocketlistsItem::class);

        if ($ids = array_unique(array_filter($data))) {
            $items_in_db = $plf->findById($ids);

            /** @var pocketlistsItem $i */
            foreach ((array) $items_in_db as $i) {
                $items[$i->getId()] = ['location_id' => $i->getLocationId()];
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
            } elseif (!array_key_exists($_item['id'], $items)) {
                $_item['success'] = true;
            }

            if (empty($_item['errors'])) {
                $_item['location_id'] = ifset($items, $_item['id'], 'location_id', null);
            } else {
                $_item['success'] = false;
            }
        }

        $items_ok = array_filter($data, function ($c) {
            return is_null($c['success']);
        });
        $items_err = array_diff_key($data, $items_ok);
        if (!empty($items_ok)) {
            $logs = [];
            $this->deleteAnnouncements($items_ok);
            foreach ($items_in_db as $item) {
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

            if ($list_ids = array_filter(array_unique(array_column($logs, 'list_id')))) {
                pl2()->getModel(pocketlistsItem::class)->updateByField(
                    ['key_list_id' => $list_ids],
                    ['activity_datetime' => date('Y-m-d H:i:s')]
                );
            }

            if ($location_ids = array_filter(array_unique(array_column($items_ok, 'location_id')))) {
                pl2()->getModel(pocketlistsLocation::class)->updateById(
                    $location_ids,
                    ['activity_datetime' => date('Y-m-d H:i:s')]
                );
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
