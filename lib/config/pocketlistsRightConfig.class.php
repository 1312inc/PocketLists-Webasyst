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
        $all_lists = $list_model->getAllLists(false);
        usort($all_lists, array($this, 'sort_archive'));
        foreach ($all_lists as $list) {
            $items[$list['id']] = $list['name'] . ($list['archived'] ? " (". _w('archived') . ")" : "");
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

    private function sort_archive($a, $b)
    {
        return $a['archived'] > $b['archived'];
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
