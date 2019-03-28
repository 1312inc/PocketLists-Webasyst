<?php

/**
 * Class pocketlistsFactoryItem
 */
class pocketlistsListFactory
{
    /**
     * @var pocketlistsListModel
     */
    protected $model;

    protected $entityClass = 'pocketlistsList';

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
        return $this->model->getAppItems($itemLinkModel->app, $itemLinkModel->entity_type, $itemLinkModel->entity_id);
    }

    /**
     * @return pocketlistsList
     */
    public function createNew()
    {
        return new $this->entityClass();
    }

    /**
     * @param array $data
     *
     * @return pocketlistsList|pocketlistsList[]
     */
    public function generateWithData(array $data, $all = false)
    {
        if ($all === false) {
            $data = [$data];
        }

        $lists = [];

        foreach ($data as $datum) {
            $lists[] = wa()->getConfig()->getHydrator()->hydrate($this->createNew(), $datum);
        }

        return $all === false ? reset($lists) : $lists;
    }
}
