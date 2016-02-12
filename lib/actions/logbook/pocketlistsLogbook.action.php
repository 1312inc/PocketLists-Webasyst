<?php

class pocketlistsLogbookAction extends waViewAction
{
    public function execute()
    {
        $im = new pocketlistsItemModel();

        $this->view->assign('items', $im->getLogbookItems());

        $this->view->assign('attachments_path', wa()->getDataUrl('attachments/', true));
    }
}
