<?php

/**
 * Class pocketlistsList
 */
class pocketlistsList extends pocketlistsItem
{
    /**
     * @var int
     */
    private $pocket_id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var bool
     */
    private $archived;

    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $color;

    /**
     * @var string|null
     */
    private $passcode;

    /**
     * @var int
     */
    private $key_item_id;

    /**
     * @var pocketlistsPocket
     */
    private $pocket;

    /**
     * @var int
     */
    private $items_count;

    /**
     * @var int
     */
    private $max_priority = 0;

    /**
     * @var string
     */
    private $min_due_date;

    /**
     * @var string
     */
    private $min_due_datetime;

    /**
     * @var pocketlistsItem[]
     */
    private $undoneItems;

    /**
     * @var pocketlistsItem[]
     */
    private $doneItems;

    /**
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function getUndoneItems()
    {
        if ($this->undoneItems === null) {
            /** @var pocketlistsItemFactory $factory */
            $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getEntityFactory(pocketlistsItem::class);

            $this->undoneItems = $factory->findUndoneByList($this);;
        }

        return $this->undoneItems;
    }

    /**
     * @param pocketlistsItem[] $undoneItems
     *
     * @return pocketlistsList
     */
    public function setUndoneItems(array $undoneItems)
    {
        $this->undoneItems = $undoneItems;

        return $this;
    }

    /**
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function getDoneItems()
    {
        if ($this->doneItems === null) {
            /** @var pocketlistsItemFactory $factory */
            $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getEntityFactory(pocketlistsItem::class);

            $this->doneItems = $factory->findDoneByList($this);
        }
        return $this->doneItems;
    }

    /**
     * @param pocketlistsItem[] $doneItems
     *
     * @return pocketlistsList
     */
    public function setDoneItems(array $doneItems)
    {
        $this->doneItems = $doneItems;

        return $this;
    }

    /**
     * @return int
     */
    public function getMaxPriority()
    {
        return $this->max_priority;
    }

    /**
     * @param int $max_priority
     *
     * @return pocketlistsList
     */
    public function setMaxPriority($max_priority)
    {
        $this->max_priority = $max_priority;

        return $this;
    }

    /**
     * @return string
     */
    public function getMinDueDate()
    {
        return $this->min_due_date;
    }

    /**
     * @param string $min_due_date
     *
     * @return pocketlistsList
     */
    public function setMinDueDate($min_due_date)
    {
        $this->min_due_date = $min_due_date;

        return $this;
    }

    /**
     * @return string
     */
    public function getMinDueDatetime()
    {
        return $this->min_due_datetime;
    }

    /**
     * @param string $min_due_datetime
     *
     * @return pocketlistsList
     */
    public function setMinDueDatetime($min_due_datetime)
    {
        $this->min_due_datetime = $min_due_datetime;

        return $this;
    }

    /**
     * @return int
     */
    public function getItemsCount()
    {
        return $this->items_count;
    }

    /**
     * @param int $items_count
     *
     * @return pocketlistsList
     */
    public function setItemsCount($items_count)
    {
        $this->items_count = $items_count;

        return $this;
    }

    /**
     * @return int
     */
    public function getPocketId()
    {
        return $this->pocket_id;
    }

    /**
     * @param int $pocket_id
     *
     * @return pocketlistsList
     */
    public function setPocketId($pocket_id)
    {
        $this->pocket_id = $pocket_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return pocketlistsList
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     *
     * @return pocketlistsList
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return bool
     */
    public function isArchived()
    {
        return $this->archived;
    }

    /**
     * @param bool $archived
     *
     * @return pocketlistsList
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     *
     * @return pocketlistsList
     */
    public function setHash($hash)
    {
        $this->hash = $hash;

        return $this;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     *
     * @return pocketlistsList
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPasscode()
    {
        return $this->passcode;
    }

    /**
     * @param string|null $passcode
     *
     * @return pocketlistsList
     */
    public function setPasscode($passcode)
    {
        $this->passcode = $passcode;

        return $this;
    }

    /**
     * @return int
     */
    public function getKeyItemId()
    {
        return $this->key_item_id;
    }

    /**
     * @param int $key_item_id
     *
     * @return pocketlistsList
     */
    public function setKeyItemId($key_item_id)
    {
        $this->key_item_id = $key_item_id;

        return $this;
    }

    /**
     * @return pocketlistsPocket
     */
    public function getPocket()
    {
        return $this->pocket;
    }

    /**
     * @param pocketlistsPocket $pocket
     *
     * @return pocketlistsList
     */
    public function setPocket($pocket)
    {
        $this->pocket = $pocket;

        return $this;
    }
}
