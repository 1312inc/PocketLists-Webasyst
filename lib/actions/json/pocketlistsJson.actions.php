<?php

/**
 * Class pocketlistsJsonActions
 */
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

    /**
     * @throws waException
     */
    public function GetItemsPocketColorAction()
    {
        $item_id = waRequest::get('id', false, waRequest::TYPE_INT);
        if (!$item_id) {
            $this->errors = 'no item id';

            return;
        }

        /** @var pocketlistsItem $item */
        $item = pl2()->getEntityFactory(pocketlistsItem::class)->findById($item_id);
        if (!$item) {
            $this->errors = 'no item id';

            return;
        }

        if ($item->getListId()) {
            if (pocketlistsRBAC::canAccessToList($item->getList())) {
                $this->response = $item->getList()->getColor();
            } else {
                $this->errors = '403 error';
            }
        } else {
            $this->response = pocketlistsStoreColor::NONE;
        }
    }

    public function heartbeatAction()
    {
        pocketlistsActivity::setUserActivity(wa()->getUser()->getId());
    }
}
