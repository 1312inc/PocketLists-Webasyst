<?php

class pocketlistsJsonActions extends waJsonActions
{
    public function defaultAction()
    {
        throw new waException('Unknown action.');
    }

    public function AppCountAction()
    {
        $pi = new pocketlistsItemModel();
        $this->response = $pi->getAppCountForUser();
    }

    public function GetListsAction()
    {
        $pocket_id = waRequest::get('id', false, waRequest::TYPE_INT);
        if ($pocket_id) {
            if ($this->getRights('pocket.'.$pocket_id) > 0) {
                $il = new pocketlistsListModel();
                $this->response = $il->getLists($pocket_id);
            }
            else {
                $this->errors = '403 error';
            }
        } else {
            $this->errors = 'no pocket id';
        }
    }
}