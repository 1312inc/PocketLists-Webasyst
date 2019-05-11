<?php

/**
 * Class pocketlistsListLazyDoneItemsAction
 */
class pocketlistsListLazyDoneItemsAction extends pocketlistsViewListAction
{
    const OFFSET = 30;

    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws pocketlistsForbiddenException
     * @throws waException
     */
    public function runAction($params = null)
    {
        $offset = waRequest::get('offset', 0, waRequest::TYPE_INT);

        $list = $this->getList();

        if (!pocketlistsRBAC::canAccessToList($list)) {
            throw new pocketlistsForbiddenException();
        }

        /** @var pocketlistsContactFactory $contactFactory */
        $contactFactory = pl2()->getEntityFactory(pocketlistsContact::class);
        $list_access_contacts = $contactFactory->getTeammates(
            pocketlistsRBAC::getAccessContacts($list),
            true,
            false,
            true
        );

        $this->user->getSettings()->set('last_pocket_list_id', json_encode(['list_id' => $list->getId()]));

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
        $done = $itemFactory->findDoneByList($list, $offset * self::OFFSET, self::OFFSET);

        $this->view->assign(
            [
                'list'                 => $list,
                'items_done'           => $done,
                'empty'                => count($done),
                'pl2_attachments_path' => wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID),
                'pocket'               => $list->getPocket(),

                'backend_url'          => pl2()->getBackendUrl(true),
                'print'                => waRequest::get('print', false),
                'list_access_contacts' => $list_access_contacts ?: [],
                'fileupload'           => 1,
            ]
        );
    }
}
