<?php

class pocketlistsArchiveAction extends  waViewAction
{
    public function execute()
    {
        $list_d = waRequest::get('id', 0, waRequest::TYPE_INT);

        if ($list_d) { // show archived list

        } else { // show completion log
            $pm = new pocketlistsPocketModel();
            $lm = new pocketlistsListModel();
            $im = new pocketlistsItemModel();

            $this->view->assign('items', $im->getCompleted());
        }
    }

}
