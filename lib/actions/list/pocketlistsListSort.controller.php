<?php

/**
 * Class pocketlistsListSortController
 */
class pocketlistsListSortController extends waJsonController
{
    /**
     * @throws waDbException
     * @throws waException
     * @throws waRightsException
     */
    public function execute()
    {
        $pocketId = waRequest::post('pocket_id', 0, waRequest::TYPE_INT);

        if (!$pocketId) {
            $this->setError(_w('Not found'));
            return;
        }

        $pocket = pocketlistsPocketModel::model()->findByPk($pocketId);
        if (!$pocket) {
            $this->setError(_w('Not found'));
            return;
        }

        if (pocketlistsRBAC::contactHasAccessToPocket($pocket->pk) !== pocketlistsRBAC::RIGHT_ADMIN) {
            throw new waRightsException(_w('Access denied'));
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
