<?php

class pocketlistsListAction extends waViewAction
{
    public function execute()
    {
        $list_id = isset($this->params['list_id']) ? $this->params['list_id'] : waRequest::get('id', false, waRequest::TYPE_INT);
        $archived = isset($this->params['archive'])  ? true : false;
        if ($list_id > 0) { // existing list
            $lm = new pocketlistsListModel();
            $list = $lm->getById($list_id);

            if (!$list) {
                return;
            }

            if (!pocketlistsHelper::canAccessToList($list['id'])) {
                throw new waException('Access denied.', 403);
            }
            $list_access_contacts = pocketlistsHelper::getAccessContactsForList($list['id']);

            $us = new pocketlistsUserSettings();
            $us->set('last_pocket_list_id', json_encode(array('list_id' => $list['id'])));

            $this->view->assign('list', $list);
            $this->view->assign('archive', $archived || $list['archived']);

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
            $this->view->assign('attachments_path', wa()->getDataUrl('attachments/', true));
            $this->view->assign('list_access_contacts', $list_access_contacts);
        } else {
            if (!pocketlistsHelper::canCreateLists()) {
                throw new waException('Access denied.', 403);
            }
            $this->view->assign('archive', $archived);
            $this->view->assign('new', true);
            $this->view->assign('empty', true);
        }
        $this->view->assign('print', waRequest::get('print', false));
    }
}
