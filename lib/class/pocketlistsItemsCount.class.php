<?php

/**
 * Class pocketlistsItemsCount
 */
class pocketlistsItemsCount
{
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
     * @var int
     */
    private $countMaxPriority = 0;

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

        foreach ($data as $priority => $count) {
            $this->count += $count;
            $this->maxPriority = max($this->maxPriority, $priority);
            $this->countPriorities[$priority] = (int) $count;

            if ($priority > pocketlistsItem::PRIORITY_NORM) {
                $this->countPriority += $count;
            }
        }

        if (isset($this->countPriorities[$this->maxPriority])) {
            $this->countMaxPriority = $this->countPriorities[$this->maxPriority];
        }
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @return int
     */
    public function getCountPriority()
    {
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
    public function getCountMaxPriority()
    {
        return (empty($this->maxPriority) ? 0 : $this->countMaxPriority);
    }

    /**
     * @return array
     */
    public function getCountPriorities()
    {
        return $this->countPriorities;
    }
}
