<?php

class pocketlistsFavoritesDateAction extends waViewAction
{
    // todo: almost same as ToDo
    public function execute()
    {
        // get pocket dots
        $im = new pocketlistsItemModel();

        $date = waRequest::get('date', false);

        // get all due or priority or assigned to me items
        $items = $im->getFavorites(wa()->getUser()->getId(), $date);

        $this->view->assign('undone_items', $items[0]);
        $this->view->assign('done_items', $items[1]);
        $this->view->assign('count_done_items', count($items[1]));

        $this->view->assign('date', $date);
        $this->view->assign('timestamp', $date ? strtotime($date) : time());

        $us = new pocketlistsUserSettings();
        $lm = new pocketlistsListModel();
        $stream_list_id = $us->getStreamInboxList();
        if ($stream_list_id && $stream_list = $lm->getById($stream_list_id)) {
            $this->view->assign("stream_list_id", $stream_list_id);
            $this->view->assign("stream_list", $stream_list);
        }

        $this->view->assign('attachments_path', wa()->getDataUrl('attachments/', true));
        $this->view->assign('this_is_stream', true);
        $this->view->assign('print', waRequest::get('print', false));
    }
}
