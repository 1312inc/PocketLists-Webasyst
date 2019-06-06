<?php

/**
 * Class pocketlistsItemLinkModel
 */
class pocketlistsItemLinkModel extends pocketlistsModel
{
    /**
     * @var string
     */
    protected $table = 'pocketlists_item_link';

    /**
     * @param string $app
     * @param string $entityId
     *
     * @return null|pocketlistsItemLinkModel|pocketlistsItemLinkModel[]
     */
    public function getLinkedItems($app, $entityId)
    {
        return $this->findByFields(['app' => $app, 'entity_id' => $entityId]);
    }

    /**
     * @param int $itemId
     *
     * @return array
     */
    public function getByItemId($itemId)
    {
        return $this
            ->select('*')
            ->where('entity_id > 0 and item_id = ?', (int)$itemId)
            ->fetchAll();
    }

    /**
     * @param $app
     * @param $entityType
     * @param $entityId
     *
     * @return int
     * @throws waException
     */
    public function countLinkedItems($app, $entityType, $entityId)
    {
        return $this->countByField(
            [
                'app'         => $app,
                'entity_type' => $entityType,
                'entity_id'   => $entityId,
            ]
        );
    }
}
