<?php

/**
 * Class pocketlistsProPluginLabelFactory
 *
 * @method pocketlistsProPluginLabelModel getModel()
 * @method pocketlistsProPluginLabel createNew()
 */
class pocketlistsProPluginLabelFactory extends pocketlistsFactory
{
    protected $entity = 'pocketlistsProPluginLabel';

    private $lastFoundCount = 0;

    /**
     * @return pocketlistsProPluginLabel
     */
    public function createNoStatus()
    {
        return $this->createNew()
            ->setName(_wp('No status'))
            ->setColor('0000000f');
    }

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
        $label = $this->getFromCache($labelId);
        if ($label instanceof pocketlistsProPluginLabel) {
            return $label;
        }

        $label = $this->findById($labelId);
        if ($label) {
            $this->cache($labelId, $label);
        }

        return $label ?: null;
    }

    /**
     * @return pocketlistsProPluginLabel[]
     * @throws waException
     */
    public function findAll()
    {
        $data = $this->getModel()->select('*')->order('sort asc, id desc')->fetchAll();

        return $this->generateWithData($data, true);
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
                'pro_label_id',
                $entity->getId(),
                ['pro_label_id' => null]
            );

            return true;
        }

        return false;
    }

    /**
     * @param pocketlistsPocket         $pocket
     * @param pocketlistsProPluginLabel $label
     *
     * @param pocketlistsContact|null   $user
     *
     * @return pocketlistsItem[]
     * @throws waDbException
     * @throws waException
     */
    public function findItemsByPocketAndLabel(
        pocketlistsPocket $pocket,
        pocketlistsProPluginLabel $label,
        pocketlistsContact $user = null
    ) {
        $user = $user instanceof pocketlistsContact ? $user : pl2()->getUser();
        $available_lists = pocketlistsRBAC::getAccessListForContact($user->getId());

        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);
        $sqlParts = $itemModel->getQueryComponents();
        $sqlParts['join'] += [
            'join pocketlists_list pl on pl.id = i.list_id',
            'join pocketlists_pocket pp on pp.id = pl.pocket_id',
            'join pocketlists_pro_label ppl on ppl.id = i.pro_label_id',
            'left join (select i2.name, l2.*
                    from pocketlists_list l2
                             JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id',
        ];
        $sqlParts['where']['and'] = [
            pocketlistsRBAC::filterListAccess($available_lists, $user->getId()),
            'pp.id = i:pocket_id',
            'i.pro_label_id = i:label_id',
            'i.status = i:status'
        ];
        $sqlParts['order by'] = ['i.status', 'i.calc_priority desc', 'i.id desc'];
        $sql = $itemModel->buildSqlComponents($sqlParts);

        $itemsData = $itemModel->query(
            $sql,
            [
                'contact_id' => wa()->getUser()->getId(),
                'pocket_id' => $pocket->getId(),
                'label_id' => $label->getId(),
                'list_ids' => $available_lists,
                'status' => 0,
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
     * @param pocketlistsPocket|null    $pocket
     * @param pocketlistsContact|null   $user
     *
     * @return pocketlistsItem[]
     * @throws waDbException
     * @throws waException
     */
    public function findItemsByLabelAndPocket(
        pocketlistsProPluginLabel $label,
        pocketlistsPocket $pocket = null,
        pocketlistsContact $user = null
    ) {
        $found = 0;
        $itemsData = $this->getItemsByLabelAndPocket(
            $label->getId(),
            $found,
            pocketlistsItem::STATUS_UNDONE,
            $pocket ? $pocket->getId() : null,
            $user instanceof pocketlistsContact ? $user->getId() : pl2()->getUser()->getId()
        );

        $this->lastFoundCount = $found;

        return pl2()->getEntityFactory(pocketlistsItem::class)->generateWithData($itemsData, true);
    }

    /**
     * @param pocketlistsPocket|null $pocket
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findDoneItemsByAllLabelsAndPocket(pocketlistsPocket $pocket = null)
    {
        $found = 0;
        $itemsData = $this->getItemsByLabelAndPocket(
            null,
            $found,
            pocketlistsItem::STATUS_DONE,
            $pocket ? $pocket->getId() : null
        );
        $this->lastFoundCount = $found;

        return pl2()->getEntityFactory(pocketlistsItem::class)->generateWithData($itemsData, true);
    }

    public function getLastFoundCount(): int
    {
        return $this->lastFoundCount;
    }

    /**
     * @param int $labelId
     * @param int $status
     * @param int $pocketId
     * @param int $contactId
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    private function getItemsByLabelAndPocket(
        $labelId,
        &$found,
        $status = pocketlistsItem::STATUS_UNDONE,
        $pocketId = 0,
        $contactId = 0
    ) {
        $contactId = $contactId ?: wa()->getUser()->getId();
        $available_lists = pocketlistsRBAC::getAccessListForContact($contactId);

        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);
        $sqlParts = $itemModel->getQueryComponents();

//        $sqlParts = $itemModel->getTodoSqlComponents($contactId, [], $available_lists);
        $sqlParts['join'] += [
            'left join (select i2.name, l2.*
                    from pocketlists_list l2
                             JOIN pocketlists_item i2 ON i2.id = l2.key_item_id) l ON l.id = i.list_id',
        ];

        if ($labelId) {
            $sqlParts['join'][] = 'join pocketlists_pro_label ppl on ppl.id = i.pro_label_id';
        }

        if ($pocketId) {
            $sqlParts['join'][] = 'join pocketlists_pocket pp on l.pocket_id = pp.id';
            $sqlParts['where']['and'][] = 'pp.id = i:pocket_id';
        }

        $sqlParts['where']['and'] = array_merge(
            $sqlParts['where']['and'],
            [
                sprintf('(%s OR l.id IS NULL)', pocketlistsRBAC::filterListAccess($available_lists, $contactId)),
                '(l.id is null OR (l.id is not null and l.archived = 0))',
                'i.status = i:status',
            ]
        );

        if ($labelId) {
            $sqlParts['where']['and'][] = 'i.pro_label_id = i:label_id';
        }

        $sqlParts['where']['or'] = [
            '(i.list_id IS NULL AND i.key_list_id IS NULL AND i.contact_id = i:contact_id) /* My to-dos to self */',
            '(i.key_list_id IS NULL AND i.assigned_contact_id = i:contact_id) /* To-dos assigned to me by other users */',
            '(i.list_id IS NOT NULL AND i.key_list_id IS NULL)',
        ];

        $sqlParts['order by'] = ['i.calc_priority desc', 'i.id asc'];

        $sqlParts['where']['and'][] = sprintf('(%s)', implode(' OR ', $sqlParts['where']['or']));
//        $sqlParts['where']['and'] = array_merge($sqlParts['where']['or'], $sqlParts['where']['and']);
        $sqlParts['where']['or'] = [];

        $sqlParts['order by'] = ['i.calc_priority desc', 'i.id'];

        $sql = $itemModel->buildSqlComponents($sqlParts, $this->getLimit(), $this->getOffset(), true);

        $this->resetLimitAndOffset();

        $data = $itemModel->query(
            $sql,
            [
                'contact_id' => $contactId,
                'label_id'   => $labelId,
                'status'     => $status,
                'pocket_id'  => $pocketId,
                'list_ids'   => $available_lists,
            ])->fetchAll();

        $found = (int)$itemModel->query('SELECT FOUND_ROWS()')->fetchField();

        return $data;
    }
}
