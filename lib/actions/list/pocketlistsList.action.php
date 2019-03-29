<?php

/**
 * Class pocketlistsListAction
 */
class pocketlistsListAction extends pocketlistsViewAction
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $list_id = isset($this->params['list_id'])
            ? $this->params['list_id']
            : waRequest::get(
                'id',
                false,
                waRequest::TYPE_INT
            );
        $pocket_id = isset($this->params['pocket_id'])
            ? $this->params['pocket_id']
            : waRequest::get(
                'pocket_id',
                false,
                waRequest::TYPE_INT
            );

        $archived = isset($this->params['archive']) ? true : false;

        /** @var pocketlistsListModel $listModel */
        $listModel = wa(pocketlistsHelper::APP_ID)->getConfig()->getModel(pocketlistsList::class);

        /** @var pocketlistsPocket $pocket */
        $pocket = wa()->getConfig()
            ->getEntityFactory(pocketlistsPocket::class)
            ->findById($pocket_id);

        $list_access_contacts = [];

        if ($list_id > 0) { // existing list
            /** @var pocketlistsList $list */
            $list =  wa()->getConfig()
                ->getEntityFactory(pocketlistsList::class)
                ->findById($list_id);

            if (!$list) {
                $this->view->assign(
                    'error',
                    [
                        'code'    => 404,
                        'message' => _w('Not found'),
                    ]
                );
                $this->setTemplate('templates/include/error.html');

                return;
            }

            if (!pocketlistsRBAC::canAccessToList($list)) {
                $this->view->assign(
                    'error',
                    [
                        'code'    => 403,
                        'message' => _w('Access denied'),
                    ]
                );
                $this->setTemplate('templates/include/error.html');

                return;
            }

            /** @var pocketlistsTeammateFactory $factory */
            $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getEntityFactory('Teammate');
            $list_access_contacts = $factory->getTeammates(
                pocketlistsRBAC::getAccessContacts($list),
                true,
                false,
                true
            );

            $us = new pocketlistsUserSettings();
            $us->set('last_pocket_list_id', json_encode(['list_id' => $list->getId()]));

            $im = new pocketlistsItemModel();
            $count_undone = $im->countByField(
                [
                    'list_id' => $list->getId(),
                    'status'  => 0,
                ]
            );
            $count_done = $im->countByField(
                [
                    'list_id' => $list->getId(),
                    'status'  => 1,
                ]
            );
            $undone = $im->getUndoneByList($list->getId());
            $done = $im->getDoneByList($list->getId(), 0, pocketlistsListLazyDoneItemsAction::OFFSET);
            $this->view->assign(
                [
                    'list'                 => $list,
                    'archive'              => $archived || $list->isArchived(),
                    'items'                => $undone,
                    'empty'                => count($undone),
                    'items_done'           => $done,
                    'count_items_done'     => $count_done,
                    'count_items_undone'   => $count_undone,
                    'new'                  => false,
                    'pl2_attachments_path' => wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID),
                    'list_icons'           => (new pocketlistsListIcon())->getAll(),            // get icons
                ]
            );
        } else {
            if (pocketlistsRBAC::contactHasAccessToPocket($pocket->getId()) != pocketlistsRBAC::RIGHT_ADMIN) {
                $this->view->assign(
                    'error',
                    [
                        'code'    => 403,
                        'message' => _w('Access denied'),
                    ]
                );
                $this->setTemplate('templates/include/error.html');

                return;
            }
            $last_list_id = $listModel->getLastListId();

            $this->view->assign(
                [
                    'archive'     => $archived,
                    'new'         => true,
                    'empty'       => true,
                    'new_list_id' => $last_list_id ? $last_list_id + 1 : 1,
                    'list'        => new pocketlistsListModel()
                ]
            );
        }

        $this->view->assign(
            [
                'backend_url'          => wa(pocketlistsHelper::APP_ID)->getConfig()->getBackendUrl(),
                'print'                => waRequest::get('print', false),
                'pocket'               => $pocket,
                'list_access_contacts' => $list_access_contacts ?: [],
                'fileupload'           => 1,
                'user'                 => $this->user
            ]
        );
    }
}
