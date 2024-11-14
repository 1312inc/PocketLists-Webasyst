<?php

/**
 * Class pocketlistsItemTagsModel
 */
class pocketlistsItemTagsModel extends pocketlistsModel
{
    /**
     * @var string
     */
    protected $table = 'pocketlists_item_tags';

    public function getTags($item_ids = [])
    {
        if (empty($item_ids) || !is_array($item_ids)) {
            return [];
        }
        $item_ids = array_unique($item_ids);
        $sql = $this->buildSqlComponents([
            'select' => ['*' => 'pt.id, pit.item_id, pt.`text`'],
            'from'   => ['pit' => 'pocketlists_item_tags pit'],
            'join'   => ['pt' => 'LEFT JOIN pocketlists_tag pt ON pt.id = pit.tag_id'],
            'where'  => [
                'and' => ['pit.item_id IN (i:item_ids)']
            ],
        ]);

        return $this->query($sql, [
            'item_ids' => $item_ids
        ])->fetchAll('id');
    }

    public function add($data = [])
    {
        if (empty($data) || !is_array($data)) {
            return;
        }

        foreach ($data as &$datum) {
            $datum += [
                
            ];
        }
    }
}
