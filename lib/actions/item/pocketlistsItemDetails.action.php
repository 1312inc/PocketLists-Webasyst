<?php

/**
 * Class pocketlistsItemDetailsAction
 */
class pocketlistsItemDetailsAction extends waViewAction
{
    /**
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $id = waRequest::post('id', false, waRequest::TYPE_INT);
        $listId = waRequest::post('list_id', false, waRequest::TYPE_INT);

        $lm = new pocketlistsListModel();
        $im = new pocketlistsItemModel();

        $list = null;
        $attachments = [];

        if ($id) {
            $am = new pocketlistsAttachmentModel();

            $item = $im->getById($id);
            $item = $im->extendItemData($item, true);

            $attachments = $am->getByField('item_id', $item['id'], true);

            /** @var pocketlistsListModel $list */
            $list = $lm->findByPk($item['list_id']);

            $this->view->assign(
                'pl2_attachments_path',
                wa()->getDataUrl('attachments/'.$item['id'].'/', true, pocketlistsHelper::APP_ID)
            );
        } else {
            $item = new pocketlistsItemModel([
                'contact_id' => wa()->getUser()->getId()
            ]);
            $item = $im->extendItemData($item, true);

            if ($listId) {
                $list = $lm->getById($listId);
            }
        }

        $this->view->assign('fileupload', $id);
        $this->view->assign('attachments', $attachments);

        $this->view->assign('item', $item);
        $this->view->assign('list', $list);

        // get contact that have access to this pocket
        $contacts = [];
        if (pocketlistsRBAC::canAssign()) {
            // if this item is from list - select only available contacts for this list
            /** @var pocketlistsTeammateFactory $factory */
            $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getModelFactory('Teammate');
            $contacts = $factory->getTeammates(
                pocketlistsRBAC::getAccessContacts($list),
                true,
                false,
                true
            );
        }

        $this->view->assign('lists', $lm->getLists());

        $this->view->assign('contacts', $contacts);
        $this->view->assign('plurl', wa()->getAppUrl(pocketlistsHelper::APP_ID));
    }
}
