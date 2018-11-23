<?php

class pocketlistsPocketSettingsDialogAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::get('id', 0, waRequest::TYPE_INT);
        if ($id > 0) {
            $pm = new pocketlistsPocketModel();
            $this->view->assign('pocket', $pm->findByPk($id));
        } else {
            $this->view->assign(
                'pocket',
                [
                    'color' => 'blue',
                ]
            );
        }
    }
}
