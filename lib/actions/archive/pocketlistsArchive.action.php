<?php

class pocketlistsArchiveAction extends waViewAction
{
    public function execute()
    {
        $lm = new pocketlistsListModel();
        $lists = $lm->getArchivedLists();

        $list_id = waRequest::get('id', 0, waRequest::TYPE_INT);
        if (!$list_id) { // get first archived list
            $list_id = reset($lists);
            $list_id = $list_id['id'];
        }

        $list = $lm->getById($list_id);
        if ($list_id !== null && $list && $list['archived']) {
            if (!pocketlistsRBAC::canAccessToList($list['id'])) {
                $this->setTemplate('templates/include/error.html');
                return;
            }
            $list_access_contacts = pocketlistsHelper::getTeammates(pocketlistsRBAC::getAccessContacts($list['id']),
                true, false);

            $this->view->assign('list', $list);
            $this->view->assign('archive', $list['archived']);

            $im = new pocketlistsItemModel();
            $count_undone = $im->countByField(array(
                'list_id' => $list_id,
                'status'  => 0,
            ));
            $count_done = $im->countByField(array(
                'list_id' => $list_id,
                'status'  => 1,
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
            $this->view->assign('list_id', $list_id);
            $this->view->assign('lists', $lists);
        }
    }
}
