<?php

class pocketlistsLogbookAction extends pocketlistsViewAction
{
    const DEFAULT_OFFSET = 30;

    public function execute()
    {
        $offset = waRequest::get('offset', 0);

        $im = new pocketlistsItemModel();

        $this->view->assign('items', $im->getLogbookItems(false, false, true, $offset * self::DEFAULT_OFFSET, self::DEFAULT_OFFSET));

        $this->view->assign('pl2_attachments_path', wa()->getDataUrl('attachments/', true, pocketlistsHelper::APP_ID));
        $this->view->assign('user', $this->user);
    }
}
