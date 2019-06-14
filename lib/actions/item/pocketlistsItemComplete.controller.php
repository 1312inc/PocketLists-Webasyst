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
        (new pocketlistsNotificationAboutCompleteItems())->notifyAboutCompleteItems($this->completed_items);

        $this->response = $item->getId();

        // log this action
        /** @var pocketlistsItem $complete_item */
        foreach ($this->completed_items as $complete_item) {
            // 3.204: self tasks @timeline
            if ($complete_item->getListId() == null && !$complete_item->getAssignedContactId()) {
                continue;
            }

            $this->logService->add(
                $this->logService->getFactory()->createNewItemLog(
                    (new pocketlistsLogContext())
                        ->setItem($complete_item)
                        ->setAction(pocketlistsLog::ACTION_COMPLETE)
                        ->setParams([pocketlistsLogContext::ITEM_ENTITY => ['complete' => $status]])
                )
            );

            $this->logAction(pocketlistsLogAction::ITEM_COMPLETED, ['item_id' => $complete_item->getId()]);
        }
    }
}
