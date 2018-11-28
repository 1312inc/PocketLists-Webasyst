<?php

/**
 * Class pocketlistsListSortController
 */
class pocketlistsListSortController extends waJsonController
{
    /**
     * @throws waDbException
     * @throws waRightsException
     */
    public function execute()
    {
        if (!wa()->getUser()->isAdmin() && !wa()->getUser()->isAdmin('pocketlists')) {
            throw new waRightsException('403');
        }

        $data = waRequest::post('data', false);

        if ($data) {
            $lm = new pocketlistsListModel();
            foreach ($data as $list) {
                $lm->updateById($list['id'], ['sort' => $list['sort']]);
            }
        }
    }
}
