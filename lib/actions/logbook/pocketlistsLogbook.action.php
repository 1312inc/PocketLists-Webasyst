<?php

class pocketlistslogbookAction extends waViewAction
{
    public function execute()
    {
        $im = new pocketlistsItemModel();

        $this->view->assign('items', $im->getCompleted());
    }
}
