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
        $list = $this->getList();

        if (pl2()->getEntityFactory(pocketlistsList::class)->remove($list)) {
            $this->response = 'ok';

            // log this action
            $this->logAction(pocketlistsLogAction::LIST_DELETED, ['list_name' => $list->getName()]);
        } else {
            $this->errors = 'error while deleting list and it items';
        }
    }
}
