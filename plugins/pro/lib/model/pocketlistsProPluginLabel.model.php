<?php

/**
 * Class pocketlistsProPluginLabelModel
 */
class pocketlistsProPluginLabelModel extends pocketlistsModel
{
    protected $table = 'pocketlists_pro_label';

    /**
     * @return array
     */
    public function getAllWithSort()
    {
        return $this->select('*')->order('sort asc, id desc')->fetchAll();
    }

    /**
     * @param array $ids
     *
     * @return bool
     */
    public function updateSort(array $ids)
    {
        $order = 0;
        foreach ($ids as $id) {
            $this->updateById($id, ['sort' => $order++]);
        }

        return true;
    }

    /**
     * @param array $data
     * @param int   $type
     *
     * @return bool|int|resource
     */
    public function insert($data, $type = 0)
    {
        $sort = $this->select('max(sort)')->fetchField();
        $data['sort'] = 1 + $sort;

        return parent::insert($data, $type);
    }

    /**
     * @param $id
     *
     * @return int
     */
    public function countItemsWithLabel($id)
    {
        return (int) $this->query('select count(id) from pocketlists_item where pro_label_id = i:id', ['id' => $id])->fetchField();
    }

    /**
     * @param int $pocketId
     *
     * @return array
     */
    public function getByPocketIdWithCount($pocketId)
    {
        return $this->query('select
                   count(pi.pro_label_id) labels_count,
                   ppl.id,
                   ppl.*
            from pocketlists_item pi
            join pocketlists_list pl on pi.list_id = pl.id
            join pocketlists_pocket pp on pl.pocket_id = pp.id
            join pocketlists_pro_label ppl on pi.pro_label_id = ppl.id
            where pi.pro_label_id > 0
                and pp.id = i:pocket_id
            group by pi.pro_label_id',
            ['pocket_id' => $pocketId]
        )->fetchAll('id', 1);
    }
}
