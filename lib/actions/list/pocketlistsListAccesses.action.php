<?php

/**
 * Class pocketlistsListDetailsAction
 */
class pocketlistsListAccessesAction extends waViewAction
{
    /**
     * @throws waDbException
     */
    public function execute()
    {
        $id = waRequest::post('id', false, waRequest::TYPE_INT);

        if ($id) {
            $list = pocketlistsListModel::model()->findByPk($id);

            /** @var pocketlistsTeammateFactory $factory */
            $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getModelFactory('Teammate');
            $list_access_contacts = $factory->getTeammates(
                pocketlistsRBAC::getAccessContacts($list->pk),
                true,
                false,
                true
            );

            $this->view->assign(compact('list', 'list_access_contacts'));
        }
    }
}
