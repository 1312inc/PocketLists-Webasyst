<?php

class pocketlistsListEmailDialogAction extends waViewAction
{
    public function execute()
    {
        $id = waRequest::get('id', false, waRequest::TYPE_INT);
        $date = waRequest::get('date', '');
        $favorite = waRequest::get('favorite', '');
        $teammate = waRequest::get('teammate', '');
        $lm = new pocketlistsListModel();
        $im = new pocketlistsItemModel();
        if ($id) {
            $list = $lm->getById($id);

            $this->view->assign('list', $list);
            $this->view->assign('items', $im->getUndoneByList($list['id']));
        } elseif ($favorite) {
            if ($date === 'today') {
                $date = false;
            }
            $items = $im->getFavorites(wa()->getUser()->getId(), $date);
            $this->view->assign('date', $date);
            $this->view->assign('items', $im->getProperSort($im->extendItemData($items[0], true)));
            $this->view->assign('favorite', true);
        } elseif ($date) {
            if ($date === 'today') {
                $date = false;
            }
            $items = $im->getToDo(wa()->getUser()->getId(), $date);
            $this->view->assign('date', $date);
            $this->view->assign('items', $items[0]);
        } elseif ($teammate) {
            $user_model = new waUserModel();
            $id = $user_model->getByLogin($teammate);
            if ($id) {
                $items = $im->getAssignedOrCompletesByContactItems($id['id']);
                $this->view->assign('teammate', $teammate);
                $this->view->assign('items', $im->getProperSort($im->extendItemData($items[0], true))); // undone
            }
        }
    }

}
