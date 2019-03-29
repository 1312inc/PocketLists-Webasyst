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
        $all = true;

        return $this->generateWithData($data, $all);
    }

    /**
     * @param int  $listId
     * @param int  $offset
     * @param int  $limit
     * @param bool $tree
     *
     * @return array|mixed
     * @throws waException
     */
    public function findDoneByList($listId, $offset = 0, $limit = 10, $tree = true)
    {
        $data = $this->getModel()->getDoneByList($listId, $offset, $limit, $tree);
        $all = true;

        return $this->generateWithData($data, $all);
    }
}
