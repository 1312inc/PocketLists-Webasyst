<?php

/**
 * Class pocketlistsList
 */
class pocketlistsPocket
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $sort;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $color;

    /**
     * @var string
     */
    private $passcode;

    /**
     * @var pocketlistsList[]
     */
    private $lists;

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
     * @param bool $checkAccess
     *
     * @return pocketlistsList[]
     * @throws waException
     */
    public function getLists()
    {
        if ($this->lists === null) {
            $this->lists = $this->fetchLists(false) ?: [];
        }

        return $this->lists;
    }

    /**
     * @return pocketlistsList[]
     */
    public function getUserLists()
    {
        if ($this->userLists === null) {
            $this->userLists = $this->fetchLists(true) ?: [];
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

    /**
     * @param $checkAccess
     *
     * @return pocketlistsList|pocketlistsList[]
     * @throws waException
     */
    protected function fetchLists($checkAccess)
    {
        /** @var pocketlistsListModel $listModel */
        $listModel = wa()->getConfig()->getModel(pocketlistsList::class);
        $lists = $listModel->getLists($checkAccess, $this->getId());

        // get all lists for this pocket
        $lists = wa()->getConfig()
            ->getEntityFactory(pocketlistsList::class)
            ->generateWithData($lists);

        if ($checkAccess) {
            $this->userLists = $lists;

            return $this->userLists;
        }

        $this->lists = $lists;

        return $this->lists;
    }
}
