<?php

class pocketlistsListAction extends waViewAction
{
    public function execute()
    {
        $list_id = isset($this->params['list_id']) ? $this->params['list_id'] : waRequest::get('id', false, waRequest::TYPE_INT);
        $archived = isset($this->params['archive'])  ? true : false;
        $lm = new pocketlistsListModel();
        if ($list_id > 0) { // existing list
            $list = $lm->getById($list_id);

            if (!$list) {
                $this->view->assign('error', array(
                    'code' => 403,
                    'message' => _w('Access denied')
                ));
                $this->setTemplate('templates/include/error.html');
                return;
            }

            if (!pocketlistsRBAC::canAccessToList($list['id'])) {
                $this->view->assign('error', array(
                    'code' => 403,
                    'message' => _w('Access denied')
                ));
                $this->setTemplate('templates/include/error.html');
                return;
            }
            $list_access_contacts = pocketlistsHelper::getTeammates(pocketlistsRBAC::getAccessContacts($list['id']), true, false);

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
            // get icons
            $this->view->assign('list_icons', pocketlistsHelper::getListIcons());
        } else {
            if (!pocketlistsRBAC::canCreateLists()) {
                $this->view->assign('error', array(
                    'code' => 403,
                    'message' => _w('Access denied')
                ));
                $this->setTemplate('templates/include/error.html');
                return;
            }
            $last_list_id = $lm->getLastListId();
            $this->view->assign('archive', $archived);
            $this->view->assign('new', true);
            $this->view->assign('empty', true);
            $this->view->assign('new_list_id', $last_list_id ? $last_list_id + 1 : 1);
        }
        $this->view->assign('backend_url', wa()->getConfig()->getBackendUrl());
        $this->view->assign('print', waRequest::get('print', false));
    }
}
