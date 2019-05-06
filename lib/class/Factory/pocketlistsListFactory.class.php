<?php

/**
 * Class pocketlistsFactoryItem
 *
 * @method pocketlistsListModel getModel()
 */
class pocketlistsListFactory extends pocketlistsFactory
{
    /**
     * @var pocketlistsNullList
     */
    protected $nullList;

    /**
     * @var string
     */
    protected $entity = 'pocketlistsList';

    /**
     * @param pocketlistsPocket $pocket
     * @param bool              $checkAccess
     *
     * @return pocketlistsList[]
     * @throws waException
     */
    public function findListsByPocket(pocketlistsPocket $pocket, $checkAccess)
    {
        $data = $this->getModel()->getAllLists($checkAccess, $pocket->getId());

//        $data = $this->calculatePriority($data);

        return $this->generateWithData($data, true);
    }

    /**
     * @return pocketlistsNullList
     */
    public function createNewNullList()
    {
        if ($this->nullList === null) {
            $this->nullList = new pocketlistsNullList();
        }

        return $this->nullList;
    }

    /**
     * @param bool $check_access
     * @param int  $pocket_id
     *
     * @return pocketlistsList[]
     * @throws waException
     */
    public function findAllActive($check_access = true, $pocket_id = 0)
    {
        $data = $this->getModel()->getAllActiveLists($check_access, $pocket_id);
        if (!$data) {
            return [];
        }

//        $data = $this->calculatePriority($data);

        $lists = $this->generateWithData($data, true);

        return (new pocketlistsStrategyListFilterAndSort($lists))->filter()->getNonArchived();
    }

    /**
     * @param bool $check_access
     * @param int  $pocket_id
     *
     * @return pocketlistsList|pocketlistsList[]
     *
     * @throws waException
     */
    public function findLists($check_access = true, $pocket_id = 0)
    {
        $data = $this->getModel()->getAllLists($check_access, $pocket_id);
        if (!$data) {
            return [];
        }

//        $data = $this->calculatePriority($data);

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsList $list
     *
     * @return bool
     * @throws waException
     */
    public function delete(pocketlistsEntity $list)
    {
        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);

        $items = $list->getItems();
        foreach ($items as $item) {
            $itemFactory->delete($item);
        }
        $itemFactory->delete($list->getKeyItem());

        /** @var pocketlistsAttachmentFactory $attachmentFactory */
        $attachmentFactory = pl2()->getEntityFactory(pocketlistsAttachment::class);
        $attachmentFactory->deleteAllByItem($list->getKeyItem());

        pl2()->getModel('pocketlistsListSort')->deleteByField('list_id', $list->getId());

        return parent::delete($list);
    }

    /**
     * @param pocketlistsList|pocketlistsEntity $list
     * @param int                               $type
     *
     * @return bool
     * @throws waException
     */
    public function save(pocketlistsEntity $list, $type = 0)
    {
        $ok = false;
        $new = $list->getId() === null;

        if (parent::save($list)) {
            // hack to save item
            $listId = $list->getId();
            $list
                ->setId(null)
                ->setKeyListId($listId);

            if ($itemId = $list->getKeyItemId()) {
                $list->setId($itemId);
            }
            $ok = pl2()->getEntityFactory(pocketlistsItem::class)->save($list);
            $itemId = $list->getId();
            $list->setId($listId);

            if (!$ok && $new) {
                $this->delete($list);
            }

            if ($ok && $new) {
                $list->setKeyItemId($itemId);
                $list->getKeyItem()->setId($itemId);
                $this->update($list, ['key_item_id']);
            }
        }

        return $ok;
    }

    /**
     * @param pocketlistsContact $teammate
     *
     * @return pocketlistsList[]
     * @throws waException
     */
    public function findForTeammate(pocketlistsContact $teammate)
    {
        $data = $this->getModel()->getTeamLists($teammate->getId());

//        $data = $this->calculatePriority($data);

        return $this->generateWithData($data, true);
    }

    /**
     * @param array $lists
     *
     * @return mixed
     */
    private function calculatePriority(array $lists)
    {
        foreach ($lists as $i => $list) {
            $list[$i]['calc_priority'] = max(
                pocketlistsHelper::calcPriorityOnDueDate($list['min_due_date'], $list['min_due_datetime']),
                $list['max_priority']
            );
        }

        return $lists;
    }
}
