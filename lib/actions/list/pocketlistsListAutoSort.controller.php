<?php

/**
 * Class pocketlistsListAutoSortController
 */
class pocketlistsListAutoSortController extends pocketlistsJsonController
{
    /**
     * @throws waDbException
     * @throws waException
     * @throws waRightsException
     */
    public function execute()
    {
        $list = $this->getList(waRequest::post('list_id', 0, waRequest::TYPE_INT));

        if (!in_array($list->getId(), pocketlistsRBAC::getAccessListForContact())) {
            throw new waRightsException('403');
        }

        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);
        $itemModel->sortItems($list->getId());
    }
}
