<?php

/**
 * Class pocketlistsListAccessesAction
 */
class pocketlistsListAccessesAction extends pocketlistsViewListAction
{
    /**
     * @param null $params
     *
     * @return mixed|void
     * @throws waException
     */
    public function runAction($params = null)
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
        $this->view->assign(
            'backend_list_accesses',
            pl2()->waDispatchEvent(new pocketlistsEvent(pocketlistsEventStorage::WA_BACKEND_LIST_ACCESSES, $list))
        );
    }
}
