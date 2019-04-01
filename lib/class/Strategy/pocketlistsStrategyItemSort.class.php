<?php

/**
 * Class pocketlistsStrategyItemSort
 */
class pocketlistsStrategyItemSort
{
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
}
