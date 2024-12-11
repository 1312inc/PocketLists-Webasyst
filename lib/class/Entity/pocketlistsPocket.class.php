<?php

/**
 * Class pocketlistsList
 */
class pocketlistsPocket extends pocketlistsEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $sort = 0;

    /**
     * @var string
     */
    private $rank = '';

    /**
     * @var string
     */
    private $name = '';

    /**
     * @var string
     */
    private $color = pocketlistsStoreColor::BLUE;

    /**
     * @var string
     */
    private $create_datetime;

    /**
     * @var string
     */
    private $update_datetime;

    /**
     * @var string
     */
    private $activity_datetime;

    /**
     * @var string
     */
    private $passcode;

    /**
     * @var string
     */
    private $uuid;

    /**
     * @var pocketlistsList[]
     */
    private $lists;

    /**
     * @var
     */
    private $listsCount;

    /**
     * @var pocketlistsList[]
     */
    private $userLists;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getListsCount()
    {
        if ($this->listsCount === null) {
            $this->listsCount = pl2()->getModel(self::class)->countLists($this->getId());
        }

        return $this->listsCount;
    }

    /**
     * @param mixed $listsCount
     *
     * @return pocketlistsPocket
     */
    public function setListsCount($listsCount)
    {
        $this->listsCount = $listsCount;

        return $this;
    }

    /**
     * @param int $id
     *
     * @return pocketlistsPocket
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param string $sort
     *
     * @return pocketlistsPocket
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return string
     */
    public function getRank()
    {
        return (string) $this->rank;
    }

    /**
     * @param string $rank
     *
     * @return $this
     */
    public function setRank($rank)
    {
        $this->rank = (string) $rank;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return pocketlistsPocket
     */
    public function setName($name)
    {
        $this->name = $name;

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
     * @return pocketlistsPocket
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreateDatetime()
    {
        return $this->create_datetime;
    }

    /**
     * @param $create_datetime
     *
     * @return pocketlistsPocket
     */
    public function setCreateDatetime($create_datetime)
    {
        $this->create_datetime = $create_datetime;

        return $this;
    }

    /**
     * @return string
     */
    public function getUpdateDatetime()
    {
        return $this->update_datetime;
    }

    /**
     * @param $update_datetime
     *
     * @return pocketlistsPocket
     */
    public function setUpdateDatetime($update_datetime)
    {
        $this->update_datetime = $update_datetime;

        return $this;
    }

    /**
     * @return string
     */
    public function getActivityDatetime()
    {
        return $this->activity_datetime;
    }

    /**
     * @param $activity_datetime
     *
     * @return pocketlistsPocket
     */
    public function setActivityDatetime($activity_datetime)
    {
        $this->activity_datetime = $activity_datetime;

        return $this;
    }

    /**
     * @return string
     */
    public function getPasscode()
    {
        return $this->passcode;
    }

    /**
     * @param string $passcode
     *
     * @return pocketlistsPocket
     */
    public function setPasscode($passcode)
    {
        $this->passcode = $passcode;

        return $this;
    }

    /**
     * @return string
     */
    public function getUuid()
    {
        return $this->uuid;
    }

    /**
     * @param $uuid
     * @return pocketlistsPocket
     */
    public function setUuid($uuid)
    {
        $this->uuid = (empty($uuid) ? null : trim($uuid));

        return $this;
    }

    /**
     * @return pocketlistsList[]
     * @throws waException
     */
    public function getLists()
    {
        if ($this->lists === null) {
            /** @var pocketlistsListFactory $factory */
            $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getEntityFactory(pocketlistsList::class);

            $this->lists = $factory->findListsByPocket($this, false);
        }

        return $this->lists;
    }

    /**
     * @return pocketlistsList[]
     * @throws waException
     */
    public function getUserLists()
    {
        if ($this->userLists === null) {
            /** @var pocketlistsListFactory $factory */
            $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getEntityFactory(pocketlistsList::class);

            $this->userLists = $factory->findListsByPocket($this, true);
        }

        return $this->userLists;
    }

    /**
     * @param pocketlistsList[] $lists
     *
     * @return pocketlistsPocket
     */
    public function setLists($lists)
    {
        $this->lists = $lists;

        return $this;
    }
}
