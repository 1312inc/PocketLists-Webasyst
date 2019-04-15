<?php

/**
 * Class pocketlistsItemDetailsAction
 */
class pocketlistsItemDetailsAction extends pocketlistsViewItemAction
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $id = waRequest::request('id', false, waRequest::TYPE_INT);
        $listId = waRequest::request('list_id', false, waRequest::TYPE_INT);

        $list = null;
        /** @var pocketlistsListFactory $listFactory */
        $listFactory = pl2()->getEntityFactory(pocketlistsList::class);

        if ($id) {
            /** @var pocketlistsItem $item */
            $item = pl2()->getEntityFactory(pocketlistsItem::class)
                ->findById($id);

            $list = $item->getList();

            $this->view->assign(
                'pl2_attachments_path',
                wa()->getDataUrl('attachments/'.$item->getId().'/', true, pocketlistsHelper::APP_ID)
            );
        } else {
            $item = new pocketlistsItem();

            if ($listId) {
                /** @var pocketlistsList $list */
                $list = wa(pocketlistsHelper::APP_ID)->getConfig()
                    ->getEntityFactory(pocketlistsList::class)
                    ->findById($id);
            }
        }

        // get contact that have access to this pocket
        $contacts = [];
        if (pocketlistsRBAC::canAssign()) {
            // if this item is from list - select only available contacts for this list
            /** @var pocketlistsContactFactory $factory */
            $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getEntityFactory(pocketlistsContact::class);
            $contacts = $factory->getTeammates(
                pocketlistsRBAC::getAccessContacts($list),
                true,
                false,
                true
            );
        }

        $this->view->assign(
            [
                'fileupload'     => $item->getId(),
                'item'           => $item,
                'list'           => $list,
                'lists'          => $listFactory->findAllActive(),
                'assign_user_id' => waRequest::post('assign_user_id', 0, waRequest::TYPE_INT)
                    ?: $item->getAssignedContactId(),
                'contacts'       => $contacts,
                'plurl'          => wa()->getAppUrl(pocketlistsHelper::APP_ID),
            ]
        );
    }
}
