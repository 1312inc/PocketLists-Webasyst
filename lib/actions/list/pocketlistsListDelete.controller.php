<?php

/**
 * Class pocketlistsListDeleteController
 */
class pocketlistsListDeleteController extends pocketlistsJsonController
{
    /**
     * @throws waException
     */
    public function execute()
    {
        /** @var pocketlistsList $list */
        $list = $this->getList();

        if (pl2()->getEntityFactory(pocketlistsList::class)->delete($list)) {
            $this->response = 'ok';

            $this->logService->add(
                $this->logService->getFactory()->createNewListLog(
                    (new pocketlistsLogContext())
                        ->setList($list)
                        ->setAction(pocketlistsLog::ACTION_DELETE)
                )
            );

            // log this action
            $this->logAction(pocketlistsLogAction::LIST_DELETED, ['list_name' => $list->getName()]);
        } else {
            $this->errors = 'error while deleting list and it items';
        }
    }
}
