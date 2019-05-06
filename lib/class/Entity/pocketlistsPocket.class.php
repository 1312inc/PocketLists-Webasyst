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
    private $name = '';

    /**
     * @var string
     */
    private $color = pocketlistsStoreColor::BLUE;

    /**
     * @var string
     */
    private $passcode;

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
     * @return int
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * @param int $sort
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
