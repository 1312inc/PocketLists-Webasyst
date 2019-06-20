<?php

/**
 * Class pocketlistsListArchiveController
 */
class pocketlistsListArchiveController extends pocketlistsJsonController
{
    /**
     * @throws pocketlistsLogicException
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function execute()
    {
        $list = $this->getList();

        $list
            ->setArchived(waRequest::post('archive', null, waRequest::TYPE_INT))
            ->setUpdateDatetime(date('Y-m-d H:i:s'));

        $updated = pl2()->getEntityFactory(pocketlistsList::class)->save($list, ['archived', 'update_datetime']);

        if ($updated) {
            $this->response = 'ok';

            $this->logService->add(
                $this->logService->getFactory()->createNewListLog(
                    (new pocketlistsLogContext())
                        ->setList($list)
                        ->setAction(
                            $list->isArchived()
                                ? pocketlistsLog::ACTION_ARCHIVE
                                : pocketlistsLog::ACTION_UNARCHIVE
                        )
                )
            );

            // log this action
            $this->logAction(
                $list->isArchived() ? pocketlistsLogAction::LIST_ARCHIVED : pocketlistsLogAction::LIST_UNARCHIVED,
                ['list_id' => $list->getId()]
            );
        } else {
            $this->errors = 'error while deleting list and his items';
        }
    }
}
