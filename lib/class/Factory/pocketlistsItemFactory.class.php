<?php

/**
 * Class pocketlistsFactoryItem
 */
class pocketlistsItemFactory
{
    /**
     * @var pocketlistsItemModel
     */
    protected $model;

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
}
