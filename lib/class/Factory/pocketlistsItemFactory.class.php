<?php

/**
 * Class pocketlistsFactoryItem
 *
 * @method pocketlistsItemModel getModel()
 */
class pocketlistsItemFactory extends pocketlistsFactory
{
    /**
     * @var pocketlistsItemModel
     */
    protected $model;

    protected $entity = 'pocketlistsItem';

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
            ->getModel(pocketlistsItem::class)
            ->getAppItems($itemLinkModel->app, $itemLinkModel->entity_type, $itemLinkModel->entity_id);
    }

    /**
     * @param pocketlistsList $list
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findUndoneByList(pocketlistsList $list)
    {
        $data = $this->getModel()->getUndoneByList($list->getId());

        return $this->generateWithList($data, $list);
    }

    /**
     * @param pocketlistsList $list
     * @param int             $offset
     * @param int             $limit
     * @param bool            $tree
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findDoneByList(pocketlistsList $list, $offset = 0, $limit = 10, $tree = true)
    {
        $data = $this->getModel()->getDoneByList($list->getId(), $offset, $limit, $tree);

        return $this->generateWithList($data, $list);
    }

    /**
     * @param pocketlistsItem[] $items
     * @param pocketlistsList    $list
     *
     * @return pocketlistsItem[]
     */
    private function generateWithList($items, pocketlistsList $list)
    {
        if (!$items) {
            return [];
        }

        /** @var pocketlistsItem[] $items */
        $items = $this->generateWithData($items, true);
        foreach ($items as $item) {
            $item->setList($list);
        }

        return $items;
    }
}
