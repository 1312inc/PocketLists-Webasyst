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
     * @param getForItem $item
     *
     * @return null|pocketlistsItemLinkModel[]
     */
    public function getForItem(pocketlistsItem $item)
    {
        return $this->model->findByFields('item_id', $item->getId(), true);
    }
}
