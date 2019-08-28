<?php

/**
 * Class pocketlistsProPluginLabelFactory
 *
 * @method pocketlistsProPluginLabelModel getModel()
 */
class pocketlistsProPluginLabelFactory extends pocketlistsFactory
{
    protected $entity;

    /**
     * @param pocketlistsItem $item
     *
     * @return pocketlistsProPluginLabel|null
     * @throws pocketlistsLogicException
     * @throws waException
     */
    public function findForItem(pocketlistsItem $item)
    {
        $labelId = $item->getDataField('pro_label_id');
        /** @var pocketlistsProPluginLabel $label */
        $label = $this->findById($labelId);

        return $label ?: null;
    }

    /**
     * @param pocketlistsProPluginLabel $entity
     *
     * @return bool
     * @throws waException
     */
    public function delete(pocketlistsEntity $entity)
    {
        if (parent::delete($entity)) {
            pl2()->getModel(pocketlistsItem::class)->updateByField(
                'pro_label',
                $entity->getId(),
                ['pro_label' => null]
            );

            return true;
        }

        return false;
    }

    /**
     * @param pocketlistsPocket         $pocket
     * @param pocketlistsProPluginLabel $label
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findItemsByPocketAndLabel(pocketlistsPocket $pocket, pocketlistsProPluginLabel $label)
    {
        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);
        $sqlParts = $itemModel->getQueryComponents();
        $sqlParts['join'] += [
            'join pocketlists_list pl on pl.id = i.list_id',
            'join pocketlists_pocket pp on pp.id = pl.pocket_id',
            'join pocketlists_pro_label ppl on ppl.id = i.pro_label_id',
        ];
        $sqlParts['where']['and'] = ['pp.id = i:pocket_id', 'i.pro_label_id = i:label_id'];
        $sql = $itemModel->buildSqlComponents($sqlParts);

        $itemsData = $itemModel->query(
            $sql,
            [
                'contact_id' => wa()->getUser()->getId(),
                'pocket_id'  => $pocket->getId(),
                'label_id'   => $label->getId(),
            ]
        )->fetchAll();

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
        $items = $itemFactory->generateWithData($itemsData, true);

        return $items;
    }

    /**
     * @param pocketlistsPocket         $pocket
     * @param pocketlistsProPluginLabel $label
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findListsByPocketAndLabel(pocketlistsPocket $pocket, pocketlistsProPluginLabel $label)
    {
        $available_lists = pocketlistsRBAC::getAccessListForContact();

        /** @var pocketlistsListModel $listModel */
        $listModel = pl2()->getModel(pocketlistsList::class);
        $sqlParts = $listModel->getAllListsSqlComponents($pocket->getId(), $available_lists);
        $sqlParts['join'] += [
            'join pocketlists_pro_label ppl on i.pro_label_id = ppl.id',
        ];
        $sqlParts['where']['and'] = ['i.pro_label_id > 0'];

        $sql = $listModel->buildSqlComponents($sqlParts);

        $listsData = $listModel->query(
            $sql,
            [
                'contact_id' => wa()->getUser()->getId(),
                'pocket_id'  => $pocket->getId(),
                'label_id'   => $label->getId(),
                'list_ids'   => $available_lists,
            ]
        )->fetchAll();

        /** @var pocketlistsListFactory $listFactory */
        $listFactory = pl2()->getEntityFactory(pocketlistsList::class);
        $lists = $listFactory->generateWithData($listsData, true);

        return $lists;
    }

    /**
     * @return pocketlistsProPluginLabel
     */
    public function createNewDone()
    {
        return $this->createNew()
            ->setName(_wp('Done'))
            ->setId(0)
            ->setColor('ccc');
    }

    /**
     * @param pocketlistsProPluginLabel $label
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findItemsByLabel(pocketlistsProPluginLabel $label)
    {
        $itemsData = $this->getItemsByLabel($label->getId());

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
        $items = $itemFactory->generateWithData($itemsData, true);

        return $items;
    }

    /**
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findDoneItemsByAllLabels()
    {
        $itemsData = $this->getItemsByLabel(null, pocketlistsItem::STATUS_DONE);

        /** @var pocketlistsItemFactory $itemFactory */
        $itemFactory = pl2()->getEntityFactory(pocketlistsItem::class);
        $items = $itemFactory->generateWithData($itemsData, true);

        return $items;
    }

    /**
     * @param int $labelId
     * @param int $status
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    private function getItemsByLabel($labelId, $status = pocketlistsItem::STATUS_UNDONE)
    {
        $contactId = wa()->getUser()->getId();
        $lists = pocketlistsRBAC::getAccessListForContact($contactId);

        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);
        $sqlParts = $itemModel->getQueryComponents();
        $sqlParts['join'] += [
            'left join (select i2.name, l2.*
                    from pocketlists_list l2
                             JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id',
        ];
        if ($labelId) {
            $sqlParts['join'][] = 'join pocketlists_pro_label ppl on ppl.id = i.pro_label_id';
        }

        $sqlParts['where']['and'] = [
            sprintf('(%s OR l.id IS NULL)', pocketlistsRBAC::filterListAccess($lists, $contactId)),
            '(l.id is null OR (l.id is not null and l.archived = 0))',
            'i.status = i:status',
        ];
        if ($labelId) {
            $sqlParts['where']['and'][] = 'i.pro_label_id = i:label_id';
        }
        $sqlParts['order by'] = ['i.calc_priority desc', 'i.id asc'];
        $sql = $itemModel->buildSqlComponents($sqlParts, $this->getLimit(), $this->getOffset());

        return $itemModel->query(
            $sql,
            [
                'contact_id' => $contactId,
                'label_id'   => $labelId,
                'status'     => $status,
            ]
        )->fetchAll();
    }
}
