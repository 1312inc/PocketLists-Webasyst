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
     * @param $item_id
     *
     * @return array
     */
    public function getByItemId($item_id)
    {
        return $this
            ->select('*')
            ->where('entity_id > 0')
            ->where('item_id = ?', (int) $item_id)
            ->where('entity_type <> ?', pocketlistsAnnouncement::ENTITY)
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

    /**
     * @param $app
     * @param $entityType
     * @param $entityId
     *
     * @return int
     */
    public function countUndoneLinkedItems($app, $entityType, $entityId)
    {
        $sql = <<<SQL
select count(pi.id) 
from pocketlists_item_link pil
join pocketlists_item pi on pil.item_id = pi.id
where pil.app = s:app
    and pil.entity_type = s:entity_type
    and pil.entity_id = i:entity_id
    and pi.status = i:status
SQL;

        return (int) $this->query(
            $sql,
            [
                'app' => $app,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'status' => pocketlistsItem::STATUS_UNDONE,
            ]
        )->fetchField();
    }

    /**
     * @param string $app
     * @param string $entityType
     * @param array  $entityIds
     *
     * @return array
     */
    public function countLinkedItemsByAppAndEntities($app, $entityType, $entityIds)
    {
        $data = $this->query(
            'select 
                count(pil.entity_id) count_entities, 
                max(pil.entity_id) entity_id
            from pocketlists_item_link pil
            join pocketlists_item i on pil.item_id = i.id and i.status = i:status
            where
                  pil.app = s:app
                 and pil.entity_type = s:entity_type
                 and pil.entity_id in (s:entity_ids)
            group by pil.entity_id',
            [
                'status'      => pocketlistsItem::STATUS_UNDONE,
                'app'         => $app,
                'entity_type' => $entityType,
                'entity_ids'  => $entityIds,
            ]
        )->fetchAll();

        return $data ?: [];
    }

    /**
     * @param $links
     * @return bool
     * @throws waException
     */
    public function setLinks($links = [])
    {
        if (empty($links) || !is_array($links)) {
            return false;
        }

        $this->exec("
            DELETE FROM {$this->table} WHERE item_id IN (i:item_ids) AND app NOT IN (s:apps)
        ", [
            'item_ids' => array_column($links, 'item_id'),
            'apps'     => [pocketlistsAnnouncement::APP],
        ]);
        $this->multipleInsert($links, 2);

        return true;
    }
}
