<?php

/**
 * Class pocketlistsStrategyItemFilter
 */
class pocketlistsStrategyItemFilterAndSort
{
    /**
     * @var pocketlistsItem[]
     */
    private $items;

    /**
     * @var pocketlistsItem[]
     */
    private $itemsDone = [];

    /**
     * @var pocketlistsItem[]
     */
    private $itemsUndone = [];

    /**
     * pocketlistsStrategyItemFilterAndSort constructor.
     *
     * @param array|null $items
     */
    public function __construct(array $items = null)
    {
        $this->items = $items;
    }

    /**
     * @param pocketlistsItem[] $items
     *
     * @return pocketlistsStrategyItemFilterAndSort
     */
    public function filterDoneUndone()
    {
        /** @var pocketlistsItem $item */
        foreach ($this->items as $item) {
            if ($item->isDone()) {
                $this->itemsDone[$item->getId()] = $item;
            } else {
                $this->itemsUndone[$item->getId()] = $item;
            }
        }

        return $this;
    }

    /**
     * @param array $items
     *
     * @return int
     */
    public function countUndone()
    {
        $count = 0;

        foreach ($this->items as $item) {
            if (!$this->isDone($item)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @return pocketlistsItem[]
     */
    public function getItemsDone()
    {
        return $this->itemsDone;
    }

    /**
     * @return pocketlistsItem[]
     */
    public function getItemsUndone()
    {
        return $this->itemsUndone;
    }

    /**
     * @return int
     */
    public function countDone()
    {
        $count = 0;

        foreach ($this->items as $item) {
            if ($this->isDone($item)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @return pocketlistsItem[]
     */
    public function getProperSort()
    {
        $items = $this->itemsDone;
        usort($this->items, [$this, 'compareForProperSort']);

        return $items;
    }

    /**
     * @return pocketlistsItem[]
     */
    public function getProperSortDone()
    {
        $items = $this->itemsDone;
        usort($items, [$this, 'compareForProperSort']);

        return $items;
    }

    /**
     * @return pocketlistsItem[]
     */
    public function getProperSortUndone()
    {
        $items = $this->itemsUndone;
        usort($items, [$this, 'compareForProperSort']);

        return $items;
    }

    /**
     * @return pocketlistsItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @param pocketlistsItem[] $items
     *
     * @return pocketlistsStrategyItemFilterAndSort
     */
    public function setItems($items)
    {
        $this->items = $items;
        $this->itemsUndone = [];
        $this->itemsDone = [];

        return $this;
    }

    /**
     * @param array|pocketlistsItem $item
     *
     * @return bool
     */
    protected function isDone($item)
    {
        return $item instanceOf pocketlistsItem ? $item->isDone() : $item['status'] == 1;
    }

    /**
     * @param pocketlistsItem $i1
     * @param pocketlistsItem $i2
     *
     * @return int
     */
    private function compareForProperSort(pocketlistsItem $i1, pocketlistsItem $i2)
    {
        if ($i1->getCalcPriority() < $i2->getCalcPriority()) {
            return 1;
        }

        if ($i1->getCalcPriority() > $i2->getCalcPriority()) {
            return -1;
        }

        $date = [];
        foreach ([$i1, $i2] as $item) {
            $date[] = $item->getDueDatetime()
                ? strtotime($item->getDueDatetime())
                : ($item->getDueDate()
                    ? strtotime($item->getDueDate())
                    : null);
        }

        // check due_date
        if ($date[0] && $date[1]) { // check both dates
            if ($date[0] < $date[1]) {
                return -1;
            }

            if ($date[0] > $date[1]) {
                return 1;
            }

            return 0;
        }

        if ($date[0] && !$date[1]) {
            return -1;
        }

        if (!$date[0] && $date[1]) {
            return 1;
        }

        $date = [];
        foreach ([$i1, $i2] as $item) {
            $date[] = $item->getUpdateDatetime() ? strtotime($item->getUpdateDatetime()) : null;
        }

        // check update_datetime
        if ($date[0] && $date[1]) { // check both dates
            if ($date[0] < $date[1]) {
                return -1;
            }

            if ($date[0] > $date[1]) {
                return 1;
            }

            return 0;
        }

        if ($date[0] && !$date[1]) {
            return -1;
        }

        if (!$date[0] && $date[1]) {
            return 1;
        }

        return $i1->getId() < $i2->getId();
    }
}
