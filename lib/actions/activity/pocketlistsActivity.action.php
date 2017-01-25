<?php

class pocketlistsActivityAction extends waViewAction
{
    public function execute()
    {
        $log_model = new waLogModel();
        $this->view->assign('activities', $log_model->getLogs(array('app_id' => "pocketlists")));
    }
}
