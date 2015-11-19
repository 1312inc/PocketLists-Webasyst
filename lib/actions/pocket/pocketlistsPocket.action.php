<?php

class pocketlistsPocketAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::get('id', 1, waRequest::TYPE_INT);
        $list_id = waRequest::get('list_id', false, waRequest::TYPE_INT);

        $cs = new waContactSettingsModel();
        $lm = new pocketlistsListModel();
        $lists = $lm->getLists($id);
        // get all lists for this pocket
        $this->view->assign('lists', $lists);

        $last_pocket_list_id = $cs->getOne(wa()->getUser()->getId(), wa()->getApp(), 'last_pocket_list_id');
        if (!$list_id) {
            if ($last_pocket_list_id) {
                $this->view->assign('last_pocket_list_id', $last_pocket_list_id);
            } else {
                if ($lists) {
                    $this->view->assign(
                        'last_pocket_list_id',
                        json_encode(array("pocket_id" => $id, "list_id" => $lists[0]['id']))
                    );
                } else {
                    $this->view->assign(
                        'last_pocket_list_id',
                        json_encode(array("pocket_id" => $id, "list_id" => "new"))
                    );
                }
            }
        } else {
            $this->view->assign('last_pocket_list_id', 0);
        }

        $this->view->assign('pocket',array('id' => 1, 'name' => 'Personal', 'class' => 'pl-dark-blue', 'indicator' => array('count' => 1, 'color' => '')));

        $this->view->assign('pockets', array(
            array('id' => 1, 'name' => 'Personal', 'class' => 'pl-dark-blue', 'indicator' => array('count' => 1, 'color' => '')),
            array('id' => 2, 'name' => 'Errands', 'class' => 'pl-dark-green', 'indicator' => array('count' => 2, 'color' => 'red')),
            array('id' => 3, 'name' => 'Msk', 'class' => 'pl-dark-red', 'indicator' => array('count' => 0, 'color' => '')),
            array('id' => 4, 'name' => 'Krasnodar', 'class' => 'pl-dark-yellow', 'indicator' => array('count' => 0, 'color' => '')),
        ));


    }
}