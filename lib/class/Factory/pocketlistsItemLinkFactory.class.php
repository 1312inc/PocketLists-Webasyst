<?php

/**
 * Class pocketlistsItemLinkFactory
 *
 * @method pocketlistsItemLinkModel getModel()
 */
class pocketlistsItemLinkFactory extends pocketlistsFactory
{
    protected $entity = 'pocketlistsItemLink';

    /**
     * @param pocketlistsItem $item
     *
     * @return pocketlistsItemLink[]
     * @throws waException
     */
    public function findForItem(pocketlistsItem $item)
    {
        $data = $this->getModel()->getByField('item_id', $item->getId(), true);

        return $this->generateWithData($data, true);
    }

    /**
     * @param string $app
     * @param string $entityType
     * @param int$entityId
     *
     * @return pocketlistsItemLink[]
     * @throws waException
     */
    public function findWithAppEntityTypeAndId($app, $entityType, $entityId)
    {
        $data = $this->getModel()->getByField([
            'app'         => $app,
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
        ], true);

        return $this->generateWithData($data, true);
    }
}
