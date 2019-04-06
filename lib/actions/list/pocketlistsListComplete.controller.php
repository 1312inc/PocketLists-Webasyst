<?php

/**
 * Class pocketlistsListCompleteController
 */
class pocketlistsListCompleteController extends pocketlistsComplete
{
    /**
     * @throws waException
     */
    public function execute()
    {
        $list = $this->getList(waRequest::post('list_id', 0, waRequest::TYPE_INT));
        $status = waRequest::post('status', 0, waRequest::TYPE_INT);

        if (!pocketlistsRBAC::canAccessToList($list)) {
            $this->setError('forbidden');

            return;
        }

        $items = $list->getUndoneItems();
        foreach ($items as $item) {
            $this->changeComplete($item, $status);
        }
        $this->changeComplete($list->getKeyItem(), $status);
        array_pop($this->completed_items);

        (new pocketlistsNotificationAboutCompleteItems())->notifyAboutCompleteItems($this->completed_items);

        $this->response = $list->getId();
    }
}
