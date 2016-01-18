<?php

class pocketlistsLogbookAction extends waViewAction
{
    public function execute()
    {
        $im = new pocketlistsItemModel();

        $this->view->assign('items', $im->getCompleted());

        $this->view->assign('attachments_path', wa()->getDataUrl('attachments/', true));
    }
}
