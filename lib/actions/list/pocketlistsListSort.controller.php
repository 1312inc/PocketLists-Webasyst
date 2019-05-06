<?php

/**
 * Class pocketlistsListSortController
 */
class pocketlistsListSortController extends pocketlistsJsonController
{
    /**
     * @throws pocketlistsForbiddenException
     * @throws pocketlistsNotFoundException
     * @throws waDbException
     * @throws waException
     */
    public function execute()
    {
        $pocket = $this->getPocket();

        if (pocketlistsRBAC::contactHasAccessToPocket($pocket) !== pocketlistsRBAC::RIGHT_ADMIN) {
            throw new pocketlistsForbiddenException();
        }

        $data = waRequest::post('data', false);

        if ($data) {
            /** @var pocketlistsListModel $listModel */
            $listModel = pl2()->getModel(pocketlistsList::class);
            foreach ($data as $list) {
                $listModel->updateById($list['id'], ['sort' => $list['sort']]);
            }
        }
    }
}
