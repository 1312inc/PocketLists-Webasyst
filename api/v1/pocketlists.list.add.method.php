<?php

class pocketlistsListAddMethod extends pocketlistsApiAbstractMethod
{
    protected $method = self::METHOD_PUT;

    public function execute()
    {
        $lists = $this->readBodyAsJson();

        if (empty($lists)) {
            throw new waAPIException('required_param', _w('Missing data'), 400);
        } elseif (!is_array($lists)) {
            throw new waAPIException('type_error', _w('Type error data'), 400);
        }

        $err = false;
        $pocket_ids = array_unique(array_column($lists, 'pocket_id'));
        $pocket_access = pocketlistsRBAC::getAccessPocketForContact($this->getUser());

        if (!empty($pocket_ids)) {
            /** @var pocketlistsPocketModel $pocket_model */
            $pocket_model = pl2()->getModel(pocketlistsPocket::class);
            $pocket_ids = $pocket_model->select('id')->where('id IN (:pocket_ids)', ['pocket_ids' => $pocket_ids])->fetchAll(null, true);
        }

        /** validate */
        foreach ($lists as &$_list) {
            /** set default */
            $_list = [
                'pocket_id' => ifset($_list, 'pocket_id', 0),
                'name'      => ifset($_list, 'name', null),
                'type'      => ifset($_list, 'type', pocketlistsList::TYPE_CHECKLIST),
                'icon'      => ifset($_list, 'icon', pocketlistsList::DEFAULT_ICON),
                'color'     => ifset($_list, 'color', pocketlistsStoreColor::NONE),
                'errors'    => []
            ];

            if (empty($_list['pocket_id'])) {
                $_list['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'pocket_id');
            } elseif (!is_numeric($_list['pocket_id'])) {
                $_list['errors'][] = _w('Invalid type pocket_id');
            } elseif ($_list['pocket_id'] < 1 || !in_array($_list['pocket_id'], $pocket_ids)) {
                $_list['errors'][] = _w('List not found');
            } elseif (!in_array($_list['pocket_id'], $pocket_access)) {
                $_list['errors'][] = _w('Pocket access denied');
            }

            if (!is_string($_list['name'])) {
                $_list['errors'][] = _w('Invalid type name');
            } elseif (!isset($_list['name'])) {
                $_list['errors'][] = sprintf_wp('Missing required parameter: “%s”.', 'name');
            }

            if (!in_array($_list['type'], ['checklist', 'notes'])) {
                $_list['errors'][] = _w('Unknown value type');
            }

            if (!array_key_exists($_list['color'], pocketlistsStoreColor::getColors())) {
                $_list['errors'][] = _w('Unknown value color');
            }

            if (empty($_list['errors'])) {
                unset($_list['errors']);
            } else {
                $err = true;
            }
        }
        unset($_list);

        if (!$err) {
            /** @var pocketlistsListFactory $list_factory */
            $list_factory = pl2()->getEntityFactory(pocketlistsList::class);

            /** @var pocketlistsList $list */
            $list = pl2()->getEntityFactory(pocketlistsList::class)->createNew();

            foreach ($lists as &$_list) {
                $list_clone = clone $list;
                $list_clone->setName($_list['name'])
                    ->setType($_list['type'])
                    ->setPocketId($_list['pocket_id'])
                    ->setColor($_list['color'])
                    ->setIcon($_list['icon'])
                    ->setContact($this->getUser())
                    ->setCreateDatetime(date('Y-m-d H:i:s'));
                $list_factory->save($list_clone);
                $_list['key_item_id'] = $list_clone->getKeyItemId();
            }
        }

        $this->response = $this->filterFields(
            $lists,
            [
                'id',
                'name',
                'contact_id',
                'pocket_id',
                'type',
                'icon',
                'color',
                'sort',
                'create_datetime',
                'update_datetime',
                'key_item_id',
            ],
            [
                'id' => 'int',
                'contact_id' => 'int',
                'pocket_id' => 'int',
                'sort' => 'int',
                'create_datetime' => 'datetime',
                'update_datetime' => 'datetime',
                'key_item_id' => 'int'
            ]
        );
    }
}
