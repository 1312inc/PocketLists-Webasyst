<?php

/**
 * Class pocketlistsListMoveToController
 */
class pocketlistsListMoveToController extends waJsonController
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

        $id = waRequest::post('id', 0, waRequest::TYPE_INT);
        $pocket_id = waRequest::post('pocket_id', 0, waRequest::TYPE_INT);

        if (!$id || !$pocket_id) {
            $this->setError('No pocket id or list id params');

            return;
        }

        /** @var pocketlistsPocketModel $pocket */
        $pocket = pocketlistsPocketModel::model()->findByPk($pocket_id);
        /** @var pocketlistsListModel $list */
        $list = pocketlistsListModel::model()->findByPk($id);

        if (!$pocket || !$list) {
            $this->setError('No pocket or list found');

            return;
        }

        if (!pocketlistsRBAC::canAccessToList($list->pk)) {
            throw new waRightsException('403');
        }

        $list->pocket_id = $pocket->pk;
        if (!$list->save(true, ['pocket_id'])) {
            $this->setError('List move error');

            return;
        }
    }
}
