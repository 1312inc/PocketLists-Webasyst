<?php

/**
 * Class pocketlistsListLazyDoneItemsAction
 */
class pocketlistsListLazyDoneItemsAction extends waViewAction
{
    const OFFSET = 30;

    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $list_id = waRequest::get('id', false, waRequest::TYPE_INT);

        $lm = new pocketlistsListModel();

        $list_access_contacts = [];

        $offset = waRequest::get('offset', 0, waRequest::TYPE_INT);

        if ($list_id > 0) { // existing list
            /** @var pocketlistsListModel $list */
            $list = $lm->findByPk($list_id);

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
            $us->set('last_pocket_list_id', json_encode(['list_id' => $list->pk]));

            $done = pocketlistsItemModel::model()->getDoneByList($list->pk, $offset * self::OFFSET, self::OFFSET);

            $pocket = pocketlistsPocketModel::model()->findByPk($list['pocket_id']);

            $this->view->assign(
                [
                    'list'                 => $list,
                    'items_done'           => $done,
                    'empty'                => count($done),
                    'pl2_attachments_path' => wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID),
                    'pocket'               => $pocket,
                ]
            );
        }

        $this->view->assign(
            [
                'backend_url'          => wa(pocketlistsHelper::APP_ID)->getConfig()->getBackendUrl(),
                'print'                => waRequest::get('print', false),
                'list_access_contacts' => $list_access_contacts ?: [],
                'fileupload'           => 1,
            ]
        );
    }
}
