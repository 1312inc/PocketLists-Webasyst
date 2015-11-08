<?php

class pocketlistsPocketAction extends waViewAction
{
    public function execute()
    {
        // get selected list items
//        $list = waRequest::get('list', false, waRequest::TYPE_INT);
//        if ($list) {
//            $list_content = wao(new pocketlistsListAction(array('list_id' => $list)))->display();
//            $this->view->assign('list_content', $list_content);
//        }

        $this->view->assign('pocket',array('id' => 1, 'name' => 'Personal', 'class' => 'pl-dark-blue', 'indicator' => array('count' => 1, 'color' => '')));

        $this->view->assign('pockets', array(
            array('id' => 1, 'name' => 'Personal', 'class' => 'pl-dark-blue', 'indicator' => array('count' => 1, 'color' => '')),
            array('id' => 2, 'name' => 'Errands', 'class' => 'pl-dark-green', 'indicator' => array('count' => 2, 'color' => 'red')),
            array('id' => 3, 'name' => 'Msk', 'class' => 'pl-dark-red', 'indicator' => array('count' => 0, 'color' => '')),
            array('id' => 4, 'name' => 'Krasnodar', 'class' => 'pl-dark-yellow', 'indicator' => array('count' => 0, 'color' => '')),
        ));

        // get all lists for this pocket
        $this->view->assign('lists', array(
            array('id' => 1, 'name' => 'Goals', 'class' => 'pl-red', 'indicator' => array('count' => 14, 'color' => ''), 'icon' => 'li-award@2x.png'),
            array('id' => 2, 'name' => 'Groceries', 'class' => 'pl-blue', 'indicator' => array('count' => 0, 'color' => 'red'), 'amount' => 5130, 'icon' => 'li-grocery@2x.png'),
            array('id' => 3, 'name' => 'Books to read', 'class' => 'pl-green', 'indicator' => array('count' => 2, 'color' => 'red'), 'icon' => 'li-books@2x.png'),
            array('id' => 4, 'name' => 'Places to visit', 'class' => '', 'indicator' => array('count' => 13, 'color' => 'yellow'), 'icon' => 'li-travel@2x.png'),
            array('id' => 5, 'name' => 'Europe trip', 'class' => 'pl-yellow', 'indicator' => array('count' => 2, 'color' => 'green'), 'icon' => 'li-flag-eu@2x.png'),
            array('id' => 5, 'name' => 'Movies to watch', 'class' => '', 'indicator' => array('count' => 1, 'color' => ''), 'icon' => 'li-video@2x.png'),
            array('id' => 6, 'name' => 'Party secret list', 'class' => 'pl-purple', 'indicator' => array('count' => 5, 'color' => 'yellow'), 'icon' => 'li-lock@2x.png'),
            array('id' => 7, 'name' => 'Gift list', 'class' => '', 'indicator' => array('count' => 2, 'color' => '', 'lock' => true), 'icon' => 'li-gift@2x.png'),
        ));

    }
}