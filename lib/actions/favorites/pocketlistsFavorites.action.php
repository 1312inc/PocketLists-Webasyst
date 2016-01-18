<?php

class pocketlistsFavoritesAction extends waViewAction
{
    public function execute()
    {
        // get pocket dots
        $im = new pocketlistsItemModel();

        $items = $im->getFavorites(wa()->getUser()->getId());

        $this->view->assign('undone_items', $items[0]);
        $this->view->assign('done_items', $items[1]);
        $this->view->assign('count_done_items', count($items[1]));

        $us = new pocketlistsUserSettings();
        $this->view->assign("stream_list_id", $us->getStreamInboxList());
    }
}
