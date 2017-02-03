<?php

class pocketlistsListEmailDialogAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::get('id', false, waRequest::TYPE_INT);
        $date = waRequest::get('date', '');
        $lm = new pocketlistsListModel();
        $im = new pocketlistsItemModel();
        if ($id) {
            $list = $lm->getById($id);

            $this->view->assign('list', $list);
            $this->view->assign('items', $im->getUndoneByList($list['id']));
        } elseif ($date) {
            if ($date === 'today') {
                $date = false;
            }
            $items = $im->getToDo(wa()->getUser()->getId(), $date);
            $this->view->assign('date', $date);
            $this->view->assign('items', $items[0]);
        }
    }

}
