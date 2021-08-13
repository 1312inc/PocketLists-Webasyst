<?php

/**
 * Class pocketlistsItemLazyDoneAction
 */
class pocketlistsItemLazyDoneAction extends pocketlistsViewAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws pocketlistsForbiddenException
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function runAction($params = null)
    {
        $offset = waRequest::get('offset', 0, waRequest::TYPE_INT);
        $type = waRequest::request('type');

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);

        switch ($type) {
            case 'list':
                $listId = waRequest::request('id', 0, waRequest::TYPE_INT);

                $list = pl2()->getEntityFactory(pocketlistsList::class)->findById($listId);
                if (!$list) {
                    throw new pocketlistsNotFoundException();
                }

                if (!pocketlistsRBAC::canAccessToList($list)) {
                    throw new pocketlistsForbiddenException();
                }

                $done = $itemFactory
                    ->setOffset($offset * pocketlistsFactory::DEFAULT_LIMIT)
                    ->setLimit(pocketlistsFactory::DEFAULT_LIMIT)
                    ->findDoneByList($list);

                break;

            case 'app':
                $app_id = waRequest::get('app');

                if (!$app_id) {
                    throw new pocketlistsNotFoundException();
                }

                /** @var pocketlistsAppLinkInterface $app */
                $app = pl2()->getLinkedApp($app_id);

                if (!$app->userCanAccess()) {
                    throw new pocketlistsForbiddenException();
                }

                $date = waRequest::request('date', false);

                $done = $itemFactory
                    ->setOffset($offset * pocketlistsFactory::DEFAULT_LIMIT)
                    ->setLimit(pocketlistsFactory::DEFAULT_LIMIT)
                    ->findDoneForApp($app, '', 0, $date);

                break;

            case 'todo':
                $date = waRequest::request('date', false);

                $done = $itemFactory
                    ->setOffset($offset * pocketlistsFactory::DEFAULT_LIMIT)
                    ->setLimit(pocketlistsFactory::DEFAULT_LIMIT)
                    ->findToDoDone($this->user, $date);

                break;

            case 'favorites':
                $date = waRequest::request('date', false);

                $done = $itemFactory
                    ->setOffset($offset * pocketlistsFactory::DEFAULT_LIMIT)
                    ->setLimit(pocketlistsFactory::DEFAULT_LIMIT)
                    ->findFavoritesDoneForUserAndDate($this->user, $date);

                break;

            case 'team':
                $teammate = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId(
                    waRequest::request('teammate', 0)
                );

                $done = $itemFactory
                    ->setOffset($offset * pocketlistsFactory::DEFAULT_LIMIT)
                    ->setLimit(pocketlistsFactory::DEFAULT_LIMIT)
                    ->findAssignedOrCompletesDoneByContact($teammate);

                break;
        }

//        /** @var pocketlistsContactFactory $contactFactory */
//        $contactFactory = pl2()->getEntityFactory(pocketlistsContact::class);
//        $list_access_contacts = $contactFactory->getTeammates(
//            pocketlistsRBAC::getAccessContacts($list),
//            true,
//            false,
//            true
//        );

//        $this->user->getSettings()->set('last_pocket_list_id', json_encode(['list_id' => $list->getId()]));

        $this->view->assign('items_done', $done);

//        $this->view->assign(
//            [
//                'list'                 => $list,
//                'items_done'           => $done,

//                'empty'                => count($done),
//                'pl2_attachments_path' => wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID),
//                'pocket'               => $list->getPocket(),

//                'backend_url'          => pl2()->getBackendUrl(true),
//                'print'                => waRequest::get('print', false),
//                'list_access_contacts' => $list_access_contacts ?: [],
//                'fileupload'           => 1,
//            ]
//        );

        $externalApp = waRequest::request('external_app');
        $template = pl2()->getUI2TemplatePath('templates/actions%s/item/ItemLazyDone.html', $externalApp);

        $this->setTemplate($template);
    }
}
