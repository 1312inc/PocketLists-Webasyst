<?php

/**
 * Class pocketlistsRightConfig
 */
class pocketlistsRightConfig extends waRightConfig
{
    const RIGHT_NONE = 0;
    const RIGHT_FULL = 1;

    /**
     * @throws waDbException
     */
    public function init()
    {
        $this->addItem(pocketlistsRBAC::CAN_CREATE_TODOS, _w('Can create to-dos to self'), 'always_enabled');
        $this->addItem(pocketlistsRBAC::CAN_CREATE_LISTS, _w('Can create shared to-do lists'), 'checkbox');
        $this->addItem(
            pocketlistsRBAC::CAN_ASSIGN,
            //_w('Can see other users personal to-dos and assign to-dos to teammates'),
            _w('Can see Shop-Script to-dos, other users personal to-dos, and assign to-dos to teammates'),
            'checkbox'
        );
        $this->addItem(
            pocketlistsRBAC::CAN_USE_SHOP_SCRIPT,
            _w('Can create and see to-dos linked with Shop-Script orders'),
            'checkbox'
        );

        // POCKETS

        // $items = [];
        // foreach (pocketlistsPocketModel::model()->getAllPockets() as $pocket) {
        //     $items[$pocket->pk] = $pocket->name;
        // }
        //
        // $this->addItem(
        //     'pocketlists',
        //     _w('Pockets'),
        //     'selectlist',
        //     [
        //         'items' => $items,
        //         'position' => 'right',
        //         'options' => [
        //             self::RIGHT_NONE => _w('No access'),
        //             //self::RIGHT_LIMITED => _w('Limited access'),
        //             self::RIGHT_FULL => _w('Full access'),
        //         ],
        //     ]
        // );

        // LISTS

        $list_model = new pocketlistsListModel();
        $items = [];
        // todo: только активные? или все подряд?
        $all_lists = $list_model->getAllLists(false);
        usort($all_lists, [$this, 'sort_archive']);
        foreach ($all_lists as $list) {
            $items[$list['id']] = ($list['archived'] ? "("._w('archive').") " : "").$list['name'];
        }

        $this->addItem(
            'list',
            _w('Lists'),
            'list',
            [
                'items' => $items,
                //                'hint1' => 'all_checkbox',
            ]
        );

    }

    /**
     * @param $a
     * @param $b
     *
     * @return bool
     */
    private function sort_archive($a, $b)
    {
        return $a['archived'] > $b['archived'];
    }

    /**
     * @param int $contact_id
     *
     * @return array
     */
    public function setDefaultRights($contact_id)
    {
        return [
            pocketlistsRBAC::CAN_CREATE_TODOS    => 1,
            pocketlistsRBAC::CAN_CREATE_LISTS    => 1,
            pocketlistsRBAC::CAN_ASSIGN          => 1,
            pocketlistsRBAC::CAN_USE_SHOP_SCRIPT => 1,
        ];
    }
}
