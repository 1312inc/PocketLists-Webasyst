<?php

class pocketlistsAppDateAction extends waViewAction
{
    public function execute()
    {
        $app_id = waRequest::get('app');

        if (!$app_id) {
            throw new waException('Not found');
        }

        $im = new pocketlistsItemModel();

        $date = waRequest::get('date', false);
        $filter = waRequest::get('filter', false);

        $items = $im->getAppItems($app_id, false, false, $date);

        $this->view->assign('undone_items', $items[0]);
        $this->view->assign('done_items', $items[1]);
        $this->view->assign('count_done_items', count($items[1]));

//        $this->view->assign('date', $date ? waDateTime::date(waDateTime::getFormat('humandate'), $date) : false);
        $this->view->assign('date', $date);
//        $this->view->assign('timestamp', $date ? strtotime($date) : (time() + 60 * 60 * 24));
        $timestamp = $date
            ? waDateTime::date('Y-m-d', strtotime($date))
            : waDateTime::date(
                'Y-m-d',
                time() + 60 * 60 * 24,
                wa()->getUser()->getTimezone()
            );
        $this->view->assign('timestamp', $timestamp);

        $us = new pocketlistsUserSettings();
        $stream_list_id = $us->getStreamInboxList();
        if ($stream_list_id) {
            $lm = new pocketlistsListModel();
            $stream_list = $lm->getById($stream_list_id);
            $this->view->assign("stream_list", $stream_list);
        }

        $this->view->assign('filter', $filter);

        $this->view->assign('pl2_attachments_path', wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID));
        $this->view->assign('this_is_stream', true);
        $this->view->assign('print', waRequest::get('print', false));

        $this->view->assign('app', wa(pocketlistsHelper::APP_ID)->getConfig()->getLinkedApp($app_id));
    }
}
