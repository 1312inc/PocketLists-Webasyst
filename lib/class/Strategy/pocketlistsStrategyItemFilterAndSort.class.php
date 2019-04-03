<?php

/**
 * Class pocketlistsStrategyItemFilter
 */
class pocketlistsStrategyItemFilterAndSort
{
    /**
     * @var pocketlistsItem[]
     */
    private $itemsDone = [];

    /**
     * @var pocketlistsItem[]
     */
    private $itemsUndone = [];

    /**
     * @param pocketlistsItem[] $items
     *
     * @return $this
     */
    public function filterDoneUndone(array $items)
    {
        /** @var pocketlistsItem $item */
        foreach ($items as $item) {
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
    public function countUndone(array $items)
    {
        $count = 0;

        foreach ($items as $item) {
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
     * @param array $items
     *
     * @return int
     */
    public function countDone(array $items)
    {
        $count = 0;

        foreach ($items as $item) {
            if ($this->isDone($item)) {
                $count++;
            }
        }

        return $count;
    }

    /**
     * @param $items
     *
     * @return mixed
     */
    public function getProperSort(array $items)
    {
        usort($items, [$this, 'compare_for_proper_sort']);

        return $items;
    }

    /**
     * @param pocketlistsItem $i1
     * @param pocketlistsItem $i2
     *
     * @return int
     */
    private function compare_for_proper_sort(pocketlistsItem $i1, pocketlistsItem $i2)
    {
        if ($i1->getCalcPriority() < $i2->getCalcPriority()) {
            return 1;
        }

        if ($i1->getCalcPriority() > $i2->getCalcPriority()) {
            return -1;
        }

        $date = [];
        foreach ([$i1, $i2] as $item) {
            $date[] = $item->getDueDatetime() ? strtotime($item['due_datetime'])
                : ($item->getDueDate() ? strtotime($item->getDueDate()) : null);
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
            $date[] = $item->getUpdateDatetime()() ? strtotime($item->getUpdateDatetime) : null;
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

        return 0;
    }

    /**
     * @param array|pocketlistsItem $item
     *
     * @return bool
     */
    protected function isDone($item)
    {
        return isset($item['id']) ? $item['status'] == 1 : $item->isDone();
    }
}
