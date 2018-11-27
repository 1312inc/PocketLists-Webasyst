<?php

/**
 * Class pocketlistsItemLinkFactory
 */
class pocketlistsItemLinkFactory
{
    /**
     * @var pocketlistsItemLinkModel
     */
    protected $model;

    /**
     * pocketlistsFactoryItemLink constructor.
     *
     * @throws waDbException
     */
    public function __construct()
    {
        $this->model = new pocketlistsItemLinkModel();
    }

    /**
     * @param pocketlistsItemModel $item
     *
     * @return null|pocketlistsItemLinkModel[]
     */
    public function getForItem(pocketlistsItemModel $item)
    {
        return $this->model->findByFields('item_id', $item->pk, true);
    }
}
