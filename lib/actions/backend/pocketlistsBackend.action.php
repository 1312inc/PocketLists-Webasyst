<?php
class pocketlistsBackendAction extends waViewAction
{
    public function execute()
    {
        
        $listname = 'Groceries';
        $this->view->assign('listname', $listname);
    }
}
