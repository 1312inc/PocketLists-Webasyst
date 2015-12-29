<?php

class pocketlistsJsonActions extends waJsonActions
{
    public function defaultAction()
    {
        throw new waException('Unknown action.');
    }

    public function AppCountAction()
    {
        $this->response = wa('pocketlists')->getConfig()->onCount();
    }

    public function GetHumandateAction()
    {
        $this->response = waDateTime::format('humandate', waRequest::get('date'));
    }

    public function GetListsAction()
    {
        $pocket_id = waRequest::get('id', false, waRequest::TYPE_INT);
        if ($pocket_id) {
            if ($this->getRights('pocket.'.$pocket_id) > 0) {
                $il = new pocketlistsListModel();
                $this->response = $il->getLists($pocket_id);
            } else {
                $this->errors = '403 error';
            }
        } else {
            $this->errors = 'no pocket id';
        }
    }
}
