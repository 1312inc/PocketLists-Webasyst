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
        ])->fetchAll();
    }

    /**
     * @param $data = [
     *      ENTITY_ID => [TAG1, TAG2, ...]
     * ]
     * @return bool
     * @throws waException
     */
    public function setTags($data = [])
    {
        if (empty($data) || !is_array($data)) {
            return false;
        }

        $tags = [];
        foreach ($data as $_d) {
            $tags = array_merge($tags, $_d);
        }
        $tags = array_unique($tags);
        $tag_model = pl2()->getModel(pocketlistsTag::class);
        $tags_in_db = $tag_model->getByField('text', $tags, 'text');

        $new_tags = array_diff($tags, array_keys($tags_in_db));
        if ($new_tags) {
            /** add new pocket tag */
            $new_tags = array_map(function ($nt) { return ['text' => $nt];}, $new_tags);
            $result = $tag_model->multipleInsert(array_values($new_tags));
            if ($result->getResult()) {
                $last_id = $result->lastInsertId();
                $rows_count = $result->affectedRows();
                if ($rows_count === count($new_tags)) {
                    foreach ($new_tags as $_new_tag) {
                        $tags_in_db[$_new_tag['text']] = [
                            'id'   => $last_id,
                            'text' => $_new_tag['text']
                        ];
                    }
                }
            }
            unset($new_tags, $tags);
        }

        $tags_insert = [];
        $tags_delete = [];
        foreach ($data as $id => $_data) {
            $tags_delete[] = $id;
            foreach ($_data as $_tag) {
                if ($_tag && $tag_id = ifset($tags_in_db, $_tag, 'id', null)) {
                    $tags_insert[] = [
                        'item_id' => $id,
                        'tag_id'  => $tag_id,
                    ];
                }

            }
        }

        $this->deleteByField('item_id', $tags_delete);
        if ($tags_insert) {
            $this->multipleInsert($tags_insert, 2);
            return true;
        }

        return false;
    }
}
