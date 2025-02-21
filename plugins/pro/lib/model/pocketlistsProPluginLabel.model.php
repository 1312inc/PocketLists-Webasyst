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
        return (int)$this->query(
            'select count(id) from pocketlists_item where pro_label_id = i:id',
            ['id' => $id]
        )->fetchField();
    }

    /**
     * @param      $pocketId
     * @param int  $itemStatus
     * @param null $contactId
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    public function getByPocketIdWithCount($pocketId, $itemStatus = pocketlistsItem::STATUS_UNDONE, $contactId = null)
    {
        if (!$contactId) {
            $contactId = pl2()->getUser()->getId();
        }
        $available_lists = pocketlistsRBAC::getAccessListForContact();

        return $this->query(
            'select
                   count(i.pro_label_id) labels_count,
                   ppl.id,
                   ppl.*
            from pocketlists_item i
            join pocketlists_list l on i.list_id = l.id
            join pocketlists_pocket pp on l.pocket_id = pp.id
            join pocketlists_pro_label ppl on i.pro_label_id = ppl.id
            where 
                i.pro_label_id > 0
                and pp.id = i:pocket_id
                and i.status = i:status
                and ' . pocketlistsRBAC::filterListAccess($available_lists, $contactId) . '
            group by i.pro_label_id',
            ['pocket_id' => $pocketId, 'status' => $itemStatus, 'list_ids' => $available_lists]
        )->fetchAll('id', 1);
    }
}
