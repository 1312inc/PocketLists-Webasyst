<?php


class pocketlistsItemDetailsAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::post('id', false, waRequest::TYPE_INT);
        if ($id) {
            $im = new pocketlistsItemModel();
            $lm = new pocketlistsListModel();
            $am = new pocketlistsAttachmentModel();
            $item = $im->getById($id);
            $item = $im->extendItemData($item, true);

            $attachments = $am->getByField('item_id', $item['id'], true);

            $list = $lm->getById($item['list_id']);
            // get contact that have access to this pocket
            $contacts = [];
            if (pocketlistsRBAC::canAssign()) {
                // if this item is from list - select only available contacts for this list
                /** @var pocketlistsTeammateFactory $factory */
                $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getModelFactory('Teammate');
                $contacts = $factory->getTeammates(
                    pocketlistsRBAC::getAccessContacts($list ? $list['id'] : 0),
                    true,
                    false,
                    true
                );
            }

            $this->view->assign('item', $item);
            $this->view->assign(
                'pl2_attachments_path',
                wa()->getDataUrl('attachments/'.$item['id'].'/', true, pocketlistsHelper::APP_ID)
            );
            $this->view->assign('attachments', $attachments);
            $this->view->assign('contacts', $contacts);

            $this->view->assign('list', $list);
            $this->view->assign('lists', $lm->getLists());

            $this->view->assign('plurl', wa()->getAppUrl(pocketlistsHelper::APP_ID));
            $this->view->assign('fileupload', 1);
        }
    }
}
