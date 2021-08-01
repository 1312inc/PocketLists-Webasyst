<?php

/**
 * Class pocketlistsBackendJsonActions
 */
class pocketlistsBackendJsonActions extends pocketlistsJsonActions
{
    /**
     * @throws waException
     */
    public function defaultAction()
    {
        throw new waException('Unknown action.');
    }

    /**
     * @throws waException
     */
    public function AppCountAction()
    {
        $this->response = pl2()->onCount(true);
    }

    /**
     * @throws waException
     */
    public function GetHumandateAction()
    {
        $this->response = waDateTime::format('humandate', waRequest::get('date'));
    }

    /**
     *
     */
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

    /**
     *
     */
    public function heartbeatAction()
    {
        pocketlistsActivity::setUserActivity(wa()->getUser()->getId());
    }

    /**
     * @throws pocketlistsNotImplementedException
     * @throws waException
     */
    public function sendNotificationsAction()
    {
        $this->response = (new pocketlistsNotificationSendService())->sendExternal();
    }

    /**
     * @throws pocketlistsNotImplementedException
     * @throws waException
     */
    public function sendDirectNotificationsAction()
    {
        $this->response = (new pocketlistsNotificationSendService())->sendInternal();
    }

    public function hideTinyAdAction()
    {
        $this->getUser()->setSettings(
            pocketlistsHelper::APP_ID,
            'hide_tiny_ad_until',
            date('Y-m-d', strtotime('+30 days'))
        );
    }

    public function getListItemCountAction()
    {
        wa()->getStorage()->close();

        $list_id = waRequest::get('id', false, waRequest::TYPE_INT);
        if (!$list_id) {
            $this->errors = 'no list id';

            return;
        }

        /** @var pocketlistsList $list */
        $list = pl2()->getEntityFactory(pocketlistsList::class)->findById($list_id);
        if (!$list) {
            $this->errors = 'no list';

            return;
        }

        if (!pocketlistsRBAC::canAccessToList($list)) {
            $this->errors = '403 error';

            return;
        }

        $count = pl2()->getEntityCounter()->getListItemsCount($list);

        $this->response = [
            'count_max_priority' => $count->getCountMaxPriority(),
            'max_priority' => $count->getMaxPriority(),
            'class' => pocketlistsViewHelper::getPriorityCssClass(
                $count->getMaxPriority(),
                pocketlistsViewHelper::CSS_CLASS_LIST_INDICATOR
            ),
            'count' => $count->getCount(),
        ];
    }
}
