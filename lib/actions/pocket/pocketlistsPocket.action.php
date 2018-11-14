<?php

class pocketlistsPocketAction extends waViewAction
{
    public function execute()
    {
        $this->view->assign(
            'pocket',
            ['id' => 1, 'name' => 'Personal', 'class' => 'pl-dark-blue', 'indicator' => ['count' => 1, 'color' => '']]
        );

        $this->view->assign(
            'pockets',
            [
                [
                    'id'        => 1,
                    'name'      => 'Personal',
                    'class'     => 'pl-dark-blue',
                    'indicator' => ['count' => 1, 'color' => ''],
                ],
                [
                    'id'        => 2,
                    'name'      => 'Errands',
                    'class'     => 'pl-dark-green',
                    'indicator' => ['count' => 2, 'color' => 'red'],
                ],
                ['id' => 3, 'name' => 'Msk', 'class' => 'pl-dark-red', 'indicator' => ['count' => 0, 'color' => '']],
                [
                    'id'        => 4,
                    'name'      => 'Krasnodar',
                    'class'     => 'pl-dark-yellow',
                    'indicator' => ['count' => 0, 'color' => ''],
                ],
            ]
        );

        // get all lists for this pocket
        $this->view->assign(
            'lists',
            [
                [
                    'id'        => 1,
                    'name'      => 'Goals',
                    'class'     => 'pl-red',
                    'indicator' => ['count' => 14, 'color' => ''],
                    'icon'      => 'li-award@2x.png',
                ],
                [
                    'id'        => 2,
                    'name'      => 'Groceries',
                    'class'     => 'pl-blue',
                    'indicator' => ['count' => 0, 'color' => 'red'],
                    'amount'    => 5130,
                    'icon'      => 'li-grocery@2x.png',
                ],
                [
                    'id'        => 3,
                    'name'      => 'Books to read',
                    'class'     => 'pl-green',
                    'indicator' => ['count' => 2, 'color' => 'red'],
                    'icon'      => 'li-books@2x.png',
                ],
                [
                    'id'        => 4,
                    'name'      => 'Places to visit',
                    'class'     => '',
                    'indicator' => ['count' => 13, 'color' => 'yellow'],
                    'icon'      => 'li-travel@2x.png',
                ],
                [
                    'id'        => 5,
                    'name'      => 'Europe trip',
                    'class'     => 'pl-yellow',
                    'indicator' => ['count' => 2, 'color' => 'green'],
                    'icon'      => 'li-flag-eu@2x.png',
                ],
                [
                    'id'        => 5,
                    'name'      => 'Movies to watch',
                    'class'     => '',
                    'indicator' => ['count' => 1, 'color' => ''],
                    'icon'      => 'li-video@2x.png',
                ],
                [
                    'id'        => 6,
                    'name'      => 'Party secret list',
                    'class'     => 'pl-purple',
                    'indicator' => ['count' => 5, 'color' => 'yellow'],
                    'icon'      => 'li-lock@2x.png',
                ],
                [
                    'id'        => 7,
                    'name'      => 'Gift list',
                    'class'     => '',
                    'indicator' => ['count' => 2, 'color' => '', 'lock' => true],
                    'icon'      => 'li-gift@2x.png',
                ],
            ]
        );

        // get selected list items
        $list = waRequest::get('list', false, waRequest::TYPE_INT);
        if ($list) {
        }
    }
}