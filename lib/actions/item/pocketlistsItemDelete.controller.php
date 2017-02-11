<?php

class pocketlistsItemDeleteController extends waJsonController
{
    public function execute()
    {
        $id = waRequest::post('id', 0, waRequest::TYPE_INT);

        if ($id) {
            $im = new pocketlistsItemModel();
            $item = $im->getById($id);

            $delete_ids = array();
            if ($item['has_children']) {
                $tree = $im->getAllByList($item['list_id'], $id);
                pocketlistsHelper::getItemChildIds($item['id'], $tree[$item['id']], $delete_ids);
            } else {
                pocketlistsHelper::getItemChildIds($item['id'], array('id' => $item['id'], 'childs' => array()), $delete_ids);
            }
            $lm = new pocketlistsItemModel();
            $lm->deleteById($delete_ids);

            // delete all attachments
            $am = new pocketlistsAttachmentModel();
            $am->delete($delete_ids);

            $cache = new waVarExportCache(pocketlistsHelper::APP_ID . '_todoItems' . wa()->getUser()->getId());
            $cache->delete();

            $this->response = array('id' => $item['id']);
        } else {
            $this->errors = 'no item error';
        }
    }
}
