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
            $contacts = array();
            if (pocketlistsRBAC::canAssign()) {
                $contacts = pocketlistsRBAC::getAccessContactsForList($list ? $list['id'] : false);
            }

            $this->view->assign('item', $item);
            $this->view->assign('attachments_path', wa()->getDataUrl('attachments/'.$item['id'].'/', true));
            $this->view->assign('attachments', $attachments);
            $this->view->assign('contacts', $contacts);

            $this->view->assign('list', $list);
            $this->view->assign('lists', $lm->getLists());
        }
    }
}
