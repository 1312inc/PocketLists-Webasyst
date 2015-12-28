<?php

class pocketlistsTodoDateAction extends waViewAction
{
    public function execute()
    {
        // get pocket dots
        $im = new pocketlistsItemModel();

        $date = waRequest::get('date', false);

        // get all due or priority or assigned to me items
        $items = $im->getToDo(wa()->getUser()->getId(), $date);
        $undone = $done = array();
        foreach ($items as $item) {
            // todo: add status const
            if ($item['status'] == 0) {
                $undone[] = $item;
            } else {
                $done[] = $item;
            }
        }

        $this->view->assign('undone_items', $undone);
        $this->view->assign('done_items', $done);

        $this->view->assign('date', $date ? waDateTime::format('humandate', $date) : false);
        $this->view->assign('timestamp', $date ? strtotime($date) : false);

        $us = new pocketlistsUserSettings();
        $this->view->assign("stream_list_id", $us->getStreamInboxList());

    }
}