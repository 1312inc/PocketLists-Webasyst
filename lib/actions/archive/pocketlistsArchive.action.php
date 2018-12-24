<?php

/**
 * Class pocketlistsArchiveAction
 */
class pocketlistsArchiveAction extends pocketlistsViewAction
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

        /** @var pocketlistsListModel $list */
        $list = $lm->findByPk($list_id);
        if ($list_id !== null && $list && $list['archived']) {
            if (!pocketlistsRBAC::canAccessToList($list)) {
                $this->view->assign(
                    'error',
                    [
                        'code' => 403,
                        'message' => _w('Access denied'),
                    ]
                );
                $this->setTemplate('templates/include/error.html');

                return;
            }

            /** @var pocketlistsTeammateFactory $factory */
            $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getModelFactory('Teammate');
            $list_access_contacts = $factory->getTeammates(
                pocketlistsRBAC::getAccessContacts($list),
                true,
                false
            );

            $this->view->assign('list', $list);
            $this->view->assign('archive', $list['archived']);

            $im = new pocketlistsItemModel();
            $count_undone = $im->countByField(
                [
                    'list_id' => $list_id,
                    'status'  => 0,
                ]
            );
            $count_done = $im->countByField(
                [
                    'list_id' => $list_id,
                    'status'  => 1,
                ]
            );
            $undone = $im->getUndoneByList($list_id);
            $done = $im->getDoneByList($list_id);
            $this->view->assign('items', $undone);
            $this->view->assign('empty', count($undone));
            $this->view->assign('items_done', $done);
            $this->view->assign('count_items_done', $count_done);
            $this->view->assign('count_items_undone', $count_undone);
            $this->view->assign('new', false);
            $this->view->assign(
                'pl2_attachments_path',
                wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID)
            );
            $this->view->assign('list_access_contacts', $list_access_contacts);
            $this->view->assign('list_id', $list_id);
            $this->view->assign('lists', $lists);
            $this->view->assign('user', $this->user);
        }
    }
}
