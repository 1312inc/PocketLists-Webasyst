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

            $list_access_contacts = pocketlistsHelper::getTeammates(
                pocketlistsRBAC::getAccessContacts($list->pk),
                true,
                false
            );

            $this->view->assign(compact('list', 'list_access_contacts'));
        }
    }
}
