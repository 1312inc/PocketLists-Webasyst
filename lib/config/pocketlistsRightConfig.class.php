<?php

class pocketlistsRightConfig extends waRightConfig
{
    const RIGHT_NONE = 0;
    const RIGHT_FULL = 1;

    public function init()
    {
        $this->addItem('cancreatetodos', _w('Can create to-dos to self'), 'always_enabled');
        $this->addItem('cancreatelists', _w('Can create shared to-do lists'), 'checkbox');
        $this->addItem('canassign', _w('Can see other users personal to-dos and assign to-dos to teammates'), 'checkbox');

        $list_model = new pocketlistsListModel();
        $items = array();
        // todo: только активные? или все подряд?
        foreach ($list_model->getAllLists(false) as $list) {
            $items[$list['id']] = $list['name'];
        }
        $this->addItem(
            'list',
            _w('Shared lists'),
            'list',
            array(
                'items'    => $items,
//                'hint1' => 'all_checkbox',
            )
        );
    }

    public function setDefaultRights($contact_id)
    {
        return array(
            'cancreatetodos' => 1,
            'cancreatelists' => 1,
            'canassign'      => 1,
        );
    }
}
