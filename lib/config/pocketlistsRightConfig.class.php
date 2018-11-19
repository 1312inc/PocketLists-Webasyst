<?php

class pocketlistsRightConfig extends waRightConfig
{
    const RIGHT_NONE = 0;
    const RIGHT_FULL = 1;

    public function init()
    {
        $this->addItem('cancreatetodos', _w('Can create to-dos to self'), 'always_enabled');
        $this->addItem('cancreatelists', _w('Can create shared to-do lists'), 'checkbox');
        $this->addItem(
            'canassign',
            _w('Can see other users personal to-dos and assign to-dos to teammates'),
            'checkbox'
        );

        // POCKETS

        $items = [];
        foreach (pocketlistsPocketModel::model()->getAllPockets() as $pocket) {
            $items[$pocket->pk] = $pocket->name;
        }

        $this->addItem(
            'pocketlists',
            _w('Pockets'),
            'selectlist',
            [
                'items' => $items,
                'position' => 'right',
                'options' => [
                    self::RIGHT_NONE => _w('No access'),
                    //self::RIGHT_LIMITED => _w('Limited access'),
                    self::RIGHT_FULL => _w('Full access'),
                ],
            ]
        );

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
            'cancreatetodos' => 1,
            'cancreatelists' => 1,
            'canassign'      => 1,
        ];
    }
}
