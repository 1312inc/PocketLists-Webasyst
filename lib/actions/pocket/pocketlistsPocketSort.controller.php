<?php

/**
 * Class pocketlistsPocketSortController
 */
class pocketlistsPocketSortController extends pocketlistsJsonController
{
    /**
     * @throws pocketlistsForbiddenException
     * @throws waException
     */
    public function execute()
    {
        if (!pocketlistsRBAC::isAdmin()) {
            throw new pocketlistsForbiddenException();
        }

        $data = waRequest::post('data', false);
        if ($data) {
            /** @var pocketlistsPocketModel $pocketModel */
            $pocketModel = pl2()->getModel(pocketlistsPocket::class);

            foreach ($data as $pocket) {
                $pocketModel->updateById($pocket['id'], ['sort' => $pocket['sort']]);
            }
        }
    }
}
