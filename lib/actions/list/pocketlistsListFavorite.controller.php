<?php

class pocketlistsListFavoriteController extends waJsonController
{
    public function execute()
    {
        $id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $status = waRequest::post('status', false);

        if ($id) {
            $ufm = new pocketlistsUserFavoritesModel();
            $im = new pocketlistsItemModel();
            $hidden_item = $im->getByField('key_list_id', $id);
            if ($status) {
                $ufm->insert(array('contact_id' => wa()->getUser()->getId(), 'item_id' => $hidden_item['id']), 2);
//                $im = new pocketlistsItemModel();
//                pocketlistsNotifications::notifyAboutCompleteItems($im->getById($id));
            } else {
                $ufm->deleteByField(array('contact_id' => wa()->getUser()->getId(), 'item_id' => $hidden_item['id']));
            }
        }
    }
}
