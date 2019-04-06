<?php

/**
 * Class pocketlistsListAccessesAction
 */
class pocketlistsListAccessesAction extends pocketlistsViewListAction
{
    /**
     * @throws waException
     */
    public function execute()
    {
        /** @var pocketlistsList $list */
        $list = $this->getList(waRequest::post('id', false, waRequest::TYPE_INT));

        /** @var pocketlistsContactFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsContact::class);
        $list_access_contacts = $factory->getTeammates(
            pocketlistsRBAC::getAccessContacts($list),
            true,
            false,
            true
        );

        $this->view->assign(compact('list', 'list_access_contacts'));
    }
}
