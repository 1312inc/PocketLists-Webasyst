<?php

class pocketlistsBackendSidebarAction extends waViewAction
{
    public function execute()
    {
        $this->view->assign('sidebar_todo_count', wa('pocketlists')->getConfig()->onCount());

        $list_model = new pocketlistsListModel();
        $this->view->assign('lists', $list_model->getAllActiveLists());

        $teammates = array();
        if ($this->getRights('canassign')) {
            $team = pocketlistsHelper::getAccessContactsForList();
            $im = new pocketlistsItemModel();
            $last_activities = $im->getContactLastActivity(array_keys($team), true);
            foreach ($last_activities as $mate_id => $last_activity) {
                $teammates[$mate_id] = array(
                    'last_activity' => $last_activity
                ) + $team[$mate_id];
                unset($team[$mate_id]);
            }
            $teammates = $teammates + $team;
        }
        $this->view->assign('team', $teammates);
    }
}