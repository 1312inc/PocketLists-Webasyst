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
        return (int) $this->query('select count(id) from pocketlists_item where pro_label = i:id', ['id' => $id])->fetchField();
    }
}
