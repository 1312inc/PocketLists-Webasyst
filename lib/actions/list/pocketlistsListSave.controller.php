<?php

/**
 * Class pocketlistsListSaveController
 */
class pocketlistsListSaveController extends pocketlistsJsonController
{
    /**
     * @throws pocketlistsNotFoundException
     * @throws waException
     */
    public function execute()
    {
        $listData = waRequest::post('list', [], waRequest::TYPE_ARRAY);

        if (!$listData) {
            return;
        }

        pocketlistsHelper::getDueDatetime($listData);

        $list = $this->getList($listData['id']);
        /** @var pocketlistsList $list */
        $list = pl2()->getHydrator()->hydrate($list, $listData);

        $list
            ->setUpdateDatetime(date("Y-m-d H:i:s"))
            ->setContact($this->user);

        $saved = pl2()->getEntityFactory(pocketlistsList::class)->save($list);
        if ($saved) {
            if ($list->getDueDate()) {
                $list->setDueDate(waDateTime::format('humandate', $list->getDueDate()));
            }

            if ($list->getDueDate()) {
                $list->setDueDatetime(waDateTime::format('humandatetime', $list->getDueDatetime()));
            }

            $this->logService->add(
                $this->logService->getFactory()->createNewListLog(
                    (new pocketlistsLogContext())->setList($list),
                    pocketlistsLog::ACTION_ADD
                )
            );

            $this->response = pl2()->getHydrator()->extract($list);
        } else {
            $this->errors = 'error while saving item';
        }
    }
}
