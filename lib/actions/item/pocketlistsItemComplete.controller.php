<?php

/**
 * Class pocketlistsItemCompleteController
 */
class pocketlistsItemCompleteController extends pocketlistsComplete
{
    /**
     * @throws waException
     */
    public function execute()
    {
        $status = waRequest::post('status', 0, waRequest::TYPE_INT);

        $item = $this->getItem();
        $this->changeComplete($item, $status);
        pocketlistsNotifications::notifyAboutCompleteItems($this->completed_items);

        $this->response = $item->getId();

        // log this action
        /** @var pocketlistsItem $complete_item */
        foreach ($this->completed_items as $complete_item) {
            // 3.204: self tasks @timeline
            if ($complete_item->getListId() == null) {
                continue;
            }

            $this->logAction(pocketlistsLogAction::ITEM_COMPLETED, ['item_id' => $complete_item->getId()]);
        }
    }
}
