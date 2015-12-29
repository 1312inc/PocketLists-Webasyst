<?php

class pocketlistsPocketSettingsDialogAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::get('pocket_id', 0, waRequest::TYPE_INT);

        if ($id > 0) {
            $pm = new pocketlistsPocketModel();
            $this->view->assign('pocket', $pm->getById($id));
        } else {
            $this->view->assign('pocket', array(
                'color' => 'blue'
            ));
        }
    }
}
