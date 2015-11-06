<?php

class pocketlistsDefaultLayout extends waLayout
{
    public function execute()
    {
        $listname = 'Groceries';
        $this->view->assign('listname', $listname);
    }
}