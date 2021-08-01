<?php

/**
 * Class pocketlistsListAction
 */
class pocketlistsListAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waDbException
     * @throws waException
     */
    public function runAction($params = null)
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
        $listModel = pl2()->getModel(pocketlistsList::class);

        /** @var pocketlistsPocket $pocket */
        $pocket = pl2()->getEntityFactory(pocketlistsPocket::class)->findById($pocket_id);

        $list_access_contacts = [];

        if ($list_id > 0) { // existing list
            /** @var pocketlistsList $list */
            $list = pl2()->getEntityFactory(pocketlistsList::class)->findById($list_id);

            if (!$list) {
                $this->view->assign(
                    'error',
                    [
                        'code'    => 404,
                        'message' => _w('Not found'),
                    ]
                );
                $this->setTemplate(pl2()->getUI2TemplatePath('templates/include%s/error.html'));

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
                $this->setTemplate(pl2()->getUI2TemplatePath('templates/include%s/error.html'));

                return;
            }

            /** @var pocketlistsContactFactory $factory */
            $factory = pl2()->getEntityFactory(pocketlistsContact::class);
            $contactIds = pocketlistsRBAC::getAccessContacts($list);
            $list_access_contacts = $factory->getTeammates(
                $contactIds,
                true,
                false,
                true
            );

            $us = new pocketlistsUserSettings();
            $us->set('last_pocket_list_id', json_encode(['list_id' => $list->getId()]));

            $im = new pocketlistsItemModel();
//            $count_undone = 0;
//            $count_done = 0;
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

//            $undone = [];
            $undone = $list->getUndoneItems();

//            $undone = $im->getUndoneByList($list->getId());
//            $done = $im->getDoneByList($list->getId(), 0, pocketlistsListLazyDoneItemsAction::OFFSET);
            $done = [];
//            $done = $list->getDoneItems();
            $this->view->assign(
                [
                    'list'               => $list,
                    'archive'            => $archived || $list->isArchived(),
                    'items'              => $undone,
                    'empty'              => count($undone),
                    'items_done'         => $done,
                    'count_items_done'   => $count_done,
                    'count_items_undone' => $count_undone,
                    'new'                => false,
                ]
            );
        } else {
            if (pocketlistsRBAC::contactHasAccessToPocket($pocket) != pocketlistsRBAC::RIGHT_ADMIN) {
                $this->view->assign(
                    'error',
                    [
                        'code'    => 403,
                        'message' => _w('Access denied'),
                    ]
                );
                $this->setTemplate(pl2()->getUI2TemplatePath('templates/include%s/error.html'));

                return;
            }
            $last_list_id = $listModel->getLastListId();

            $this->view->assign(
                [
                    'archive'     => $archived,
                    'new'         => true,
                    'empty'       => true,
                    'new_list_id' => $last_list_id ? $last_list_id + 1 : 1,
                    'list'        => pl2()->getEntityFactory(pocketlistsList::class)->createNew(),
                    'count_items_done'   => 0,
                    'count_items_undone' => 0,
                ]
            );
        }

        $this->view->assign(
            [
                'print'                => waRequest::get('print', false),
                'pocket'               => $pocket,
                'list_access_contacts' => $list_access_contacts ?: [],
                'fileupload'           => 1,
                'user'                 => $this->user,
                'list_icons'           => (new pocketlistsListIcon())->getAll(),            // get icons
                'itemAdd'              => (new pocketlistsItemAddAction())->display(false),
            ]
        );
    }
}
