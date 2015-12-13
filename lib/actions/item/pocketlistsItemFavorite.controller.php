<?php

class pocketlistsItemFavoriteController extends waJsonController
{
    public function execute()
    {
        $id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $status = waRequest::post('status', false);

        if ($id) {
            $ufm = new pocketlistsUserFavoritesModel();
            if ($status) {
                $ufm->insert(array('contact_id' => wa()->getUser()->getId(), 'item_id' => $id));
//                $im = new pocketlistsItemModel();
//                pocketlistsNotifications::notifyAboutCompleteItems($im->getById($id));
            } else {
                $ufm->deleteByField(array('contact_id' => wa()->getUser()->getId(), 'item_id' => $id));
            }
        }

    }
}