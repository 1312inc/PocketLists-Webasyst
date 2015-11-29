<?php

class pocketlistsPocketAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::get('id', 0, waRequest::TYPE_INT);
        $list_id = waRequest::get('list_id', false, waRequest::TYPE_INT);

        $cs = new waContactSettingsModel();
        $pm = new pocketlistsPocketModel();
        $lm = new pocketlistsListModel();

        $last_pocket_list_id = json_decode(
            $cs->getOne(wa()->getUser()->getId(), wa()->getApp(), 'last_pocket_list_id'),
            true
        );

        if (!$id) {
            if (isset($last_pocket_list_id['pocket_id'])) {
                $id = $last_pocket_list_id['pocket_id'];
            } else {
                $id = 1;
            }
        }

        $pocket = $pm->getById($id);
        // get all lists for this pocket
        $lists = $lm->getLists($pocket['id']);

        if (!$list_id) {
            if ($list_id < 0 && isset($last_pocket_list_id['list_id']) && $last_pocket_list_id['pocket_id'] == $pocket['id']) {
                $list_id = $last_pocket_list_id['list_id'];
            } else {
                if ($lists) {
                    $list_id = $lists[0]['id'];
                    $last_pocket_list_id = array("pocket_id" => $id, "list_id" => $list_id);
                } else {
                    $last_pocket_list_id = array("pocket_id" => $id);
                }
            }
        }

        if ($list_id != -1) {
            $cs->set(
                wa()->getUser()->getId(),
                wa()->getApp(),
                'last_pocket_list_id',
                json_encode($last_pocket_list_id)
            );
        }
        $lists_html = wao(new pocketlistsListAction(array('list_id' => $list_id)))->display();
        $this->view->assign('lists_html', $lists_html);

        $this->view->assign('lists', $lists);
        $this->view->assign('list_id', $list_id);
        $this->view->assign('pocket', $pocket);
        $this->view->assign('pockets', $pm->getAllPockets(wa()->getUser()));
    }
}