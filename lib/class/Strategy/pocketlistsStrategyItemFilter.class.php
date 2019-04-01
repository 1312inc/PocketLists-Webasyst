<?php

/**
 * Class pocketlistsStrategyItemFilter
 */
class pocketlistsStrategyItemFilter
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
     * @param array $items
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
     * @param array|pocketlistsItem $item
     *
     * @return bool
     */
    protected function isDone($item)
    {
        return isset($item['id']) ? $item['status'] == 1 : $item->isDone();
    }
}
