<?php

class pocketlistsRightConfig extends waRightConfig
{
    const RIGHT_NONE = 0;
    const RIGHT_FULL = 1;

    public function init()
    {
        $pm = new pocketlistsPocketModel();
        $items = array();
        foreach ($pm->getAllPockets() as $pocket) {
            $items[$pocket['id']] = $pocket['name'];
        }
        $this->addItem(
            'pocket',
            _w('Pocketlists'),
            'selectlist',
            array(
                'items' => $items,
                'position' => 'right',
                'options' => array(
                    self::RIGHT_NONE => _w('No access'),
                    self::RIGHT_FULL => _w('Full access'),
                ),
            )
        );
    }
}
