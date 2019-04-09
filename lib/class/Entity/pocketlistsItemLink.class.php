<?php

/**
 * Class pocketlistsComment
 */
class pocketlistsItemLink extends pocketlistsEntity
{
    const LIMIT = 10;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $item_id;

    /**
     * @var string
     */
    private $app = '';

    /**
     * @var string
     */
    private $entity_type = '';

    /**
     * @var string
     */
    private $entity_id = '';

    /**
     * @var array
     */
    private $data = [];

    /**
     * @var pocketlistsItem
     */
    private $item;

    /**
     * @var pocketlistsAppLinkInterface
     */
    private $link;

    /**
     * @return pocketlistsItem
     * @throws waException
     */
    public function getItem()
    {
        if ($this->item === null && $this->item_id) {
            $this->item = pl2()->getEntityFactory(pocketlistsItem::class)->findById($this->item_id);
        }

        return $this->item;
    }

    /**
     * @param pocketlistsItem $item
     *
     * @return pocketlistsItemLink
     */
    public function setItem(pocketlistsItem $item)
    {
        $this->item = $item;
        $this->item_id = $this->item->getId();

        return $this;
    }

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
     * @return pocketlistsItemLink
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getItemId()
    {
        return $this->item_id;
    }

    /**
     * @param int $item_id
     *
     * @return pocketlistsItemLink
     */
    public function setItemId($item_id)
    {
        $this->item_id = $item_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getApp()
    {
        return $this->app;
    }

    /**
     * @param string $app
     *
     * @return pocketlistsItemLink
     */
    public function setApp($app)
    {
        $this->app = $app;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityType()
    {
        return $this->entity_type;
    }

    /**
     * @param string $entity_type
     *
     * @return pocketlistsItemLink
     */
    public function setEntityType($entity_type)
    {
        $this->entity_type = $entity_type;

        return $this;
    }

    /**
     * @return string
     */
    public function getEntityId()
    {
        return $this->entity_id;
    }

    /**
     * @param string $entity_id
     *
     * @return pocketlistsItemLink
     */
    public function setEntityId($entity_id)
    {
        $this->entity_id = $entity_id;

        return $this;
    }

    /**
     * @return array
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param array $data
     *
     * @return pocketlistsItemLink
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return pocketlistsItemLink
     */
    public function addData($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @return pocketlistsAppLinkInterface
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * @param pocketlistsAppLinkInterface $link
     *
     * @return pocketlistsItemLink
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }
}
