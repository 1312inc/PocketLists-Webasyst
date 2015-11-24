<?php

class pocketlistsListAction extends waViewAction
{
    public function execute()
    {
        $list_id = isset($this->params['list_id']) ? $this->params['list_id'] : waRequest::get('id', false, waRequest::TYPE_INT);

        if ($list_id) { // existing list
            $lm = new pocketlistsListModel();
            $list = $lm->getList($list_id);

            $cs = new waContactSettingsModel();
            $cs->set(
                wa()->getUser()->getId(),
                wa()->getApp(),
                'last_pocket_list_id',
                json_encode(array('pocket_id' => $list['pocket_id'], 'list_id' => $list['id']))
            );

            $this->view->assign('list', $list);

            $im = new pocketlistsItemModel();
            $this->view->assign('items', $im->getUndoneByList($list_id));
            $this->view->assign('items_done', $im->getDoneByList($list_id));
        }
        $this->view->assign('new', $list_id ? false : true);
    }
}