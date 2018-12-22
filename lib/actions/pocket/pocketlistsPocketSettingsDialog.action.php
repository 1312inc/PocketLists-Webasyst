<?php

class pocketlistsPocketSettingsDialogAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::get('id', 0, waRequest::TYPE_INT);
        if ($id > 0) {
            $this->view->assign('pocket', pocketlistsPocketModel::model()->findByPk($id));
        } else {
            $this->view->assign(
                'pocket',
                new pocketlistsPocketModel(['color' => 'blue',])
            );
        }
    }
}
