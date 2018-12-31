<?php

/**
 * Class pocketlistsListMoveToController
 */
class pocketlistsListMoveToController extends waJsonController
{
    /**
     * @throws waDbException
     * @throws waException
     * @throws waRightsException
     */
    public function execute()
    {
        $id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $pocket_id = waRequest::post('pocket_id', 0, waRequest::TYPE_INT);

        if (!$id || !$pocket_id) {
            $this->setError(_w('No pocket/id and list/id params specified'));

            return;
        }

        /** @var pocketlistsPocketModel $pocket */
        $pocket = pocketlistsPocketModel::model()->findByPk($pocket_id);
        /** @var pocketlistsListModel $list */
        $list = pocketlistsListModel::model()->findByPk($id);

        if (!$pocket || !$list) {
            $this->setError(_w('Pocket and list not found'));

            return;
        }

        if (pocketlistsRBAC::contactHasAccessToPocket($pocket->pk) != pocketlistsRBAC::RIGHT_ADMIN) {
            $this->setError(_w('Access denied'));

            return;
        }

        if (!pocketlistsRBAC::canAccessToList($list)) {
            $this->setError(_w('Access denied'));

            return;
        }

        $list->pocket_id = $pocket->pk;
        if (!$list->save(true, ['pocket_id'])) {
            $this->setError(_w('List move error'));

            return;
        }
    }
}
