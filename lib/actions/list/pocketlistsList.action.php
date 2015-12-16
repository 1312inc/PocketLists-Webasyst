<?php

class pocketlistsListAction extends waViewAction
{
    public function execute()
    {
        $list_id = isset($this->params['list_id']) ? $this->params['list_id'] : waRequest::get('id', false, waRequest::TYPE_INT);

        if ($list_id > 0) { // existing list
            $lm = new pocketlistsListModel();
            $list = $lm->getById($list_id);

            $available_pockets = pocketlistsHelper::getAccessPocketForContact();
            if (!in_array($list['pocket_id'], $available_pockets)) {
                throw new waException('Access denied.', 403);
            }

            $us = new pocketlistsUserSettings();
            $us->set('last_pocket_list_id', json_encode(array('pocket_id' => $list['pocket_id'], 'list_id' => $list['id'])));

            $this->view->assign('list', $list);

            $im = new pocketlistsItemModel();
            $count_undone = $im->countByField(array(
                'list_id' => $list_id,
                'status' => 0
            ));
            $count_done = $im->countByField(array(
                'list_id' => $list_id,
                'status' => 1
            ));
            $undone = $im->getUndoneByList($list_id);
            $done = $im->getDoneByList($list_id);
            $this->view->assign('items', $undone);
            $this->view->assign('empty', count($undone));
            $this->view->assign('items_done', $done);
            $this->view->assign('count_items_done', $count_done);
            $this->view->assign('count_items_undone', $count_undone);
            $this->view->assign('new', false);
        } else {
            $this->view->assign('new', true);
            $this->view->assign('empty', true);
        }
    }
}