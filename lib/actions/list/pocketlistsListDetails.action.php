<?php

/**
 * Class pocketlistsListDetailsAction
 */
class pocketlistsListDetailsAction extends waViewAction
{
    /**
     * @throws waDbException
     */
    public function execute()
    {
        $id = waRequest::post('id', false, waRequest::TYPE_INT);
        if ($id) {
            $list = pocketlistsListModel::model()->findByPk($id);
            $this->view->assign('list', $list);
        }
    }
}
