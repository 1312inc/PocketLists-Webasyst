<?php

/**
 * Class pocketlistsItemDetailsAction
 */
class pocketlistsItemDetailsAction extends pocketlistsViewItemAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waException
     */
    public function runAction($params = null)
    {
        $id = waRequest::request('id', false, waRequest::TYPE_INT);
        $listId = waRequest::request('list_id', false, waRequest::TYPE_INT);
        $caller = waRequest::request('caller', '', waRequest::TYPE_STRING_TRIM);
        $externalApp = waRequest::request('external_app', null, waRequest::TYPE_STRING_TRIM);

        $list = null;
        /** @var pocketlistsListFactory $listFactory */
        $listFactory = pl2()->getEntityFactory(pocketlistsList::class);

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
        if ($id) {
            /** @var pocketlistsItem $item */
            $item = $itemFactory->findById($id);

            $list = $item->getList();

            $this->view->assign(
                'pl2_attachments_path',
                wa()->getDataUrl('attachments/'.$item->getId().'/', true, pocketlistsHelper::APP_ID)
            );
        } else {
            /** @var pocketlistsItem $item */
            $item = $itemFactory->createNew();
            $item->setContact($this->user);

            if ($listId) {
                /** @var pocketlistsList $list */
                $list = $listFactory->findById($listId);
                if ($list) {
                    $item->setList($list);
                }
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

        $assign_user_id = $item->getAssignedContactId();
        if (!$assign_user_id) {
            $assign_user_id = waRequest::post('assign_user_id', 0, waRequest::TYPE_INT);
        }

        /**
         * @event backend_item_add.detail
         *
         * @param pocketlistsEventInterface $event Event with pocketlistsItem as object
         *
         * @return string of html output
         */
        $event = new pocketlistsEvent(
            pocketlistsEventStorage::WA_BACKEND_ITEM_ADD_DETAIL,
            $item,
            ['externalApp' => $externalApp]
        );
        $eventResult = pl2()->waDispatchEvent($event);

        /** @var pocketlistsPocketFactory $pocketFactory */
        $pocketFactory = pl2()->getEntityFactory(pocketlistsPocket::class);
        $allPockets = $pocketFactory->findAllForUser();
        /** @var pocketlistsListDetailsListsDto[] $lists */
        $lists = [];
        foreach ($allPockets as $pocket) {
            foreach ($pocket->getUserLists() as $list) {
                $lists[] = new pocketlistsListDetailsListsDto(
                    $list->getId(),
                    $list->getNameParsed(),
                    $pocket->getName()
                );
            }
        }

        $this->view->assign(
            [
                'fileupload' => true,//$item->getId(),
                'item' => $item,
                'list' => $list,
                'lists' => $lists,
                'assign_user_id' => $assign_user_id,
                'contacts' => $contacts,
                'caller' => $caller,

                'backend_item_add' => $eventResult,
            ]
        );

        $template = pl2()->getUI2TemplatePath('templates/actions%s/item/ItemDetails.html', $externalApp);

        $this->setTemplate($template);
    }
}
