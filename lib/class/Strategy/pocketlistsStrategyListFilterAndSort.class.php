<?php

/**
 * Class pocketlistsStrategyListFilter
 */
class pocketlistsStrategyListFilterAndSort
{
    /**
     * @var pocketlistsList[]
     */
    private $lists = [];

    /**
     * @var pocketlistsList[]
     */
    private $unArchived = [];

    /**
     * @var pocketlistsList[]
     */
    private $archived = [];

    /**
     * pocketlistsStrategyListFilter constructor.
     *
     * @param array $lists
     */
    public function __construct(array $lists = [])
    {
        $this->lists = $lists;
    }

    /**
     * @return pocketlistsStrategyListFilterAndSort
     */
    public function filter()
    {
        /** @var pocketlistsList $list */
        foreach ($this->lists as $i => $list) {
            if ((int)$list->isArchived() === 0) {
                $this->unArchived[] = $list;
            }

            if ((int)$list->isArchived() > 0) {
                $this->archived[] = $list;
            }
        }

        return $this;
    }

    /**
     * @return pocketlistsList[]
     */
    public function getLists()
    {
        return $this->lists;
    }

    /**
     * @param pocketlistsList[] $lists
     *
     * @return pocketlistsStrategyListFilterAndSort
     */
    public function setLists($lists)
    {
        $this->lists = $lists;

        return $this;
    }

    /**
     * @return pocketlistsList[]
     */
    public function getNonArchived()
    {
        return $this->unArchived;
    }

    /**
     * @param pocketlistsList[] $nonArchived
     *
     * @return pocketlistsStrategyListFilterAndSort
     */
    public function setNonArchived($nonArchived)
    {
        $this->unArchived = $nonArchived;

        return $this;
    }

    /**
     * @return pocketlistsList[]
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * @param pocketlistsList[] $archived
     *
     * @return pocketlistsStrategyListFilterAndSort
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * @return pocketlistsList[]
     */
    public function sortUnarchivedByActivity()
    {
        usort($this->unArchived, [$this, 'sortByActivity']);

        return $this->unArchived;
    }

    /**
     * @param pocketlistsList[] $lists
     *
     * @return pocketlistsList[] $lists
     */
    public function sortByPocketAndList(array $lists)
    {
        $sorted = [];
        foreach ($lists as $list) {
            if (!isset($sorted[$list->getPocket()->getSort()])) {
                $sorted[$list->getPocket()->getSort()] = [];
            }if (!isset($sorted[$list->getPocket()->getSort()][$list->getSort()])) {
                $sorted[$list->getPocket()->getSort()][$list->getSort()] = [];
            }

            $sorted[$list->getPocket()->getSort()][$list->getSort()][] = $list;
        }

        $result = array_reduce($sorted, [pocketlistsHelper::class, 'arrayFlatten']);

        return $result;
    }

    /**
     * @param pocketlistsList $a
     * @param pocketlistsList $b
     *
     * @return bool
     */
    private function sortByActivity(pocketlistsList $a, pocketlistsList $b)
    {
        return strtotime($a->getLastContactAtivity()) < strtotime($b->getLastContactAtivity());
    }
}
