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
        if ($this->getRights('list.%') > 0) {
            $il = new pocketlistsListModel();
            $this->response = $il->getLists();
        } else {
            $this->errors = '403 error';
        }
    }

    public function GetItemsPocketColorAction()
    {
        $item_id = waRequest::get('id', false, waRequest::TYPE_INT);
        if ($item_id) {
            $im = new pocketlistsItemModel();
            $item = $im->getById($item_id);

            if ($item['list_id']) {
                $lm = new pocketlistsListModel();
                $list = $lm->getById($item['list_id']);
                if ($this->getRights('list.' . $list['id']) > 0) {
                    $this->response = $list['color'];
                } else {
                    $this->errors = '403 error';
                }
            } else {
                $this->response = pocketlistsHelper::COLOR_DEFAULT;
            }
        } else {
            $this->errors = 'no item id';
        }
    }
}
