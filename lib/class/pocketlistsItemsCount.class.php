<?php

/**
 * Class pocketlistsItemsCount
 */
class pocketlistsItemsCount implements pocketlistsHydratableInterface
{
    /**
     * @var int
     */
    private $count;

    /**
     * @var int
     */
    private $countPriority;

    /**
     * @var int
     */
    private $maxPriority;

    /**
     * pocketlistsItemsCount constructor.
     *
     * @param int $count
     * @param int $countPriority
     * @param int $maxPriority
     */
    public function __construct($count = 0, $countPriority = 0, $maxPriority = pocketlistsItem::PRIORITY_NORM)
    {
        $this->count = $count;
        $this->countPriority = $countPriority;
        $this->maxPriority = $maxPriority;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     *
     * @return pocketlistsItemsCount
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return int
     */
    public function getCountPriority()
    {
        return $this->countPriority;
    }

    /**
     * @param int $countPriority
     *
     * @return pocketlistsItemsCount
     */
    public function setCountPriority($countPriority)
    {
        $this->countPriority = $countPriority;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxPriority()
    {
        return $this->maxPriority;
    }

    /**
     * @param int $maxPriority
     *
     * @return pocketlistsItemsCount
     */
    public function setMaxPriority($maxPriority)
    {
        $this->maxPriority = $maxPriority;

        return $this;
    }

    public function afterHydrate($data = [])
    {
    }

    public function beforeExtract(array &$fields)
    {
    }
}
