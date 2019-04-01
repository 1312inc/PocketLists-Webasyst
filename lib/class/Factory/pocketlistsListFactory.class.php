<?php

/**
 * Class pocketlistsFactoryItem
 *
 * @method pocketlistsListModel getModel()
 */
class pocketlistsListFactory extends pocketlistsFactory
{
    /**
     * @var pocketlistsListModel
     */
    protected $model;

    /**
     * @var pocketlistsNullList
     */
    protected $nullList;

    protected $entity = 'pocketlistsList';

    /**
     * pocketlistsFactoryItem constructor.
     *
     * @throws waDbException
     */
    public function __construct()
    {
        $this->model = new pocketlistsItemModel();
    }

    /**
     * @param pocketlistsItemLinkModel $itemLinkModel
     *
     * @return array
     */
    public function findForLinkedEntity(pocketlistsItemLinkModel $itemLinkModel)
    {
        return wa()->getConfig()
            ->getModel(pocketlistsList::class)
            ->getAppItems($itemLinkModel->app, $itemLinkModel->entity_type, $itemLinkModel->entity_id);
    }

    /**
     * @param true $pocketId
     * @param bool $checkAccess
     *
     * @return pocketlistsList[]
     * @throws waException
     */
    public function findListsByPocketId($pocketId, $checkAccess)
    {
        $data = $this->getModel()->getLists($checkAccess, $pocketId);

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

        $data = $this->calculatePriority($data);

        $lists = $this->generateWithData($data, true);

        return (new pocketlistsStrategyListFilter())->filterArchive($lists);
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
        $data = $this->getModel()->getLists($check_access, $pocket_id);
        if (!$data) {
            return [];
        }

        $lists = $this->generateWithData($data);

        return $lists;
    }

    /**
     * @param array $lists
     *
     * @return mixed
     */
    private function calculatePriority(array $lists)
    {
        foreach ($lists as $list) {
            $list['calc_priority'] = max(
                pocketlistsHelper::calcPriorityOnDueDate($list['min_due_date'], $list['min_due_datetime']),
                $list['max_priority']
            );
        }

        return $lists;
    }
}
