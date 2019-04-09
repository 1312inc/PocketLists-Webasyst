<?php

/**
 * Class pocketlistsItemLinkModel
 *
 * @property int    $item_id
 * @property string $app
 * @property string $entity_type
 * @property int    $entity_id
 * @property string $data
 */
class pocketlistsItemLinkModel extends kmModelExt
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
