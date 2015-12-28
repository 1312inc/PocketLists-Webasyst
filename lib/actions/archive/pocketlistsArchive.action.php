<?php

class pocketlistsArchiveAction extends  waViewAction
{
    public function execute()
    {
        $lm = new pocketlistsListModel();
        $lists = $lm->getArchived();

        $list_id = waRequest::get('id', 0, waRequest::TYPE_INT);
        if (!$list_id) { // get first archived list
            $list_id = reset($lists);
            $list_id = $list_id['id'];
        }

        $lists_html = wao(new pocketlistsListAction(array('list_id' => $list_id, 'archive' => true)))->display();
        $this->view->assign('lists_html', $lists_html);

        $this->view->assign('lists', $lists);
        $this->view->assign('list_id', $list_id);
        $this->view->assign('archive', true);
    }

}
