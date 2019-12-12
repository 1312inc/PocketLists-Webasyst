<?php

/**
 * Class pocketlistsEventListenerItemCreate
 */
class pocketlistsEventListenerItemSave
{
    /**
     * @var waLogModel
     */
    private $walog;

    /**
     * @param pocketlistsEventItemsSave $event
     *
     * @throws waDbException
     * @throws waException
     */
    public function notifyAndLogAboutNew(pocketlistsEventItemsSave $event)
    {
        $items = $event->getItems();

        (new pocketlistsNotificationAboutNewItems())->notify($items, $event->getList());

        $logService = pl2()->getLogService();

        // log this action
        foreach ($items as $item) {
            $logService->add(
                $logService->getFactory()->createNewAfterItemAdd(
                    (new pocketlistsLogContext())
                        ->setList($event->getList())
                        ->setItem($item, pocketlistsLogContext::ITEM_MORE_INFO_APP_LINKS)
                )
            );
        }

        if ($event->getList() instanceof pocketlistsList && $event->getList()->getId()) {
            if (count($items) === 1) {
                $item = reset($items);
                $this->getWaLogModel()->add(
                    pocketlistsLogAction::NEW_ITEM,
                    [
                        'item_id' => $item->getId(),
                        'list_id' => $event->getList()->getId(),
                    ]
                );
            } else {
                $this->getWaLogModel()->add(pocketlistsLogAction::NEW_ITEMS, ['list_id' => $event->getList()->getId()]);
            }
        }
    }

    /**
     * @param pocketlistsEventItemsSave $event
     *
     * @throws waDbException
     * @throws waException
     */
    public function logAboutUpdated(pocketlistsEventItemsSave $event)
    {
        $items = $event->getItems();
        $logService = pl2()->getLogService();
        $params = $event->getParams();
        $itemsOld = ifempty($params, 'itemsOld', []);

        // log this action
        foreach ($items as $item) {
            $logContext = new pocketlistsLogContext();

            $logContext
                ->setList($item->getList())
                ->setItem($item, pocketlistsLogContext::ITEM_MORE_INFO_APP_LINKS);

            if (!empty($itemsOld[$item->getId()])) {
                $logContext->addParam(
                    [
                        'item_old' => pl2()->getHydrator()->extract(
                            $itemsOld[$item->getId()],
                            pocketlistsLogContext::ITEM_FIELDS_TO_EXTRACT
                        ),
                    ]
                );
            }

            if ($item->getAssignedContactId() && $item->getAssignedContactId() != $event->getOldAssignContactId()) {
                $logContext->addParam(['item_action' => pocketlistsLog::ITEM_ACTION_NEW_ASSIGN]);
            }

            $logService->add(
                $logService->getFactory()->createNewAfterItemUpdate($logContext)
            );
        }
    }

    /**
     * @param pocketlistsEventItemsSave $event
     *
     * @throws waException
     */
    public function notifyAndLogAboutNewAssign(pocketlistsEventItemsSave $event)
    {
        $items = $event->getItems();

        foreach ($items as $item) {
            if (!$event->getAssignContactId()) {
                continue;
            }

            if ($event->getOldAssignContactId() == $event->getAssignContactId()) {
                continue;
            }

//            $us = new pocketlistsUserSettings($item->getAssignedContactId());
            // settings are set AND assigned id is updated
//            if ($us->emailWhenNewAssignToMe())

            (new pocketlistsNotificationAboutNewAssign())->notify($item);

            $this->getWaLogModel()->add(
                pocketlistsLogAction::ITEM_ASSIGN_TEAM,
                [
                    'list_id'     => $item->getListId(),
                    'item_id'     => $item->getId(),
                    'assigned_to' => $item->getAssignedContactId(),
                ]
            );
        }
    }

    /**
     * @return waLogModel
     * @throws waException
     */
    private function getWaLogModel()
    {
        if ($this->walog === null) {
            if (!class_exists('waLogModel')) {
                wa('webasyst');
            }

            /** @var waLogModel $walog */
            $this->walog = pl2()->getModel('waLog');
        }

        return $this->walog;
    }
}
