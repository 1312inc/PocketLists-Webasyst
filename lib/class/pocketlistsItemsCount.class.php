<?php

/**
 * Class pocketlistsItemsCount
 */
class pocketlistsItemsCount
{
    const DEFAULT = [
        pocketlistsItem::PRIORITY_BURNINHELL => 0,
        pocketlistsItem::PRIORITY_BLACK      => 0,
        pocketlistsItem::PRIORITY_RED        => 0,
        pocketlistsItem::PRIORITY_YELLOW     => 0,
        pocketlistsItem::PRIORITY_GREEN      => 0,
        pocketlistsItem::PRIORITY_NORM       => 0
    ];

    /**
     * @var int
     */
    private $count = 0;

    /**
     * @var int
     */
    private $countPriority = 0;

    /**
     * @var int
     */
    private $maxPriority = 0;

    /**
     * @var array
     */
    private $countPrivate = [];

    /**
     * @var array
     */
    private $countPriorities = [];

    /**
     * pocketlistsItemsCount constructor.
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        if (empty($data)) {
            return;
        }
        $this->countPrivate = array_map('intval', ifset($data, 'private_items_count', []));
        unset($data['private_items_count']);

        foreach ($data as $priority => $count) {
            $this->count += $count;
            $this->maxPriority = max($this->maxPriority, $priority);
            $this->countPriorities[$priority] = (int) $count;

            if ($priority > pocketlistsItem::PRIORITY_NORM) {
                $this->countPriority += $count;
            }
        }

        $this->countPrivate += self::DEFAULT;
        $this->countPriorities += self::DEFAULT;
    }

    /**
     * @param $with_private
     * @return int
     */
    public function getCount($with_private = false)
    {
        if ($with_private) {
            return $this->count - array_sum($this->countPrivate);
        }

        return $this->count;
    }

    /**
     * @param $with_private
     * @return int
     */
    public function getCountPriority($with_private = false)
    {
        if ($with_private) {
            return $this->countPriority - array_sum($this->countPrivate) + $this->countPrivate[pocketlistsItem::PRIORITY_NORM];
        }

        return $this->countPriority;
    }

    /**
     * @return int
     */
    public function getMaxPriority()
    {
        return $this->maxPriority;
    }

    /**
     * @return int
     */
    public function getCountMaxPriority($with_private = false)
    {
        if ($with_private) {
            return ifempty($this->countPriorities, $this->maxPriority, 0) - ifempty($this->countPrivate, $this->maxPriority, 0);
        }

        return (empty($this->maxPriority) ? 0 : ifempty($this->countPriorities, $this->maxPriority, 0));
    }

    /**
     * @return array
     */
    public function getCountPrivate()
    {
        return $this->countPrivate;
    }

    /**
     * @param $with_private
     * @return array
     */
    public function getCountPriorities($with_private = false)
    {
        if ($with_private) {
            $result = [];
            foreach ($this->countPriorities as $priority => $count) {
                $result[$priority] = (int) $count - ifset($this->countPrivate, $priority, 0);
            }
            return $result;
        }

        return $this->countPriorities;
    }
}
