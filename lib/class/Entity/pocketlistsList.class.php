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
