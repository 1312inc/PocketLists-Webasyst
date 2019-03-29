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
}
