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
     * @var int
     */
    private $entity_id = 0;

    /**
     * @var string|null
     */
    private $data = null;

    /**
     * @var array
     */
    private $dataArray = [];

    /**
     * @var pocketlistsItem
     */
    private $item;

    /**
     * @var pocketlistsAppLinkInterface
     */
    private $link;

    /**
     * @var shopOrder
     */
    private $appEntity;

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
     * @param int $entity_id
     *
     * @return pocketlistsItemLink
     */
    public function setEntityId($entity_id)
    {
        $this->entity_id = (int)$entity_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @return array
     */
    public function getDataArray()
    {
        return $this->dataArray;
    }

    /**
     * @param string $data
     *
     * @return pocketlistsItemLink
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }
    /**
     * @param array $data
     *
     * @return pocketlistsItemLink
     */
    public function setDataArray(array $data)
    {
        $this->dataArray = $data;

        return $this;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return pocketlistsItemLink
     */
    public function addDataArray($key, $value)
    {
        $this->dataArray[$key] = $value;

        return $this;
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

    /**
     * @return string
     */
    public function __toString()
    {
        return ucfirst($this->getEntityType()).' #'.$this->getEntityId();
    }

    /**
     * @return pocketlistsAppLinkInterface
     * @throws waException
     */
    public function getAppLink()
    {
        $linkedApp = pl2()->getLinkedApp($this->getApp());
        if (!$linkedApp instanceof pocketlistsAppLinkInterface) {
           return  pl2()->getLinkedApp(time());
        }

        return $linkedApp;
    }

    /**
     * @return shopOrder|tasksTask
     * @throws waException
     */
    public function getAppEntity()
    {
        if ($this->appEntity === null) {
            $this->appEntity = $this->getAppLink()->getAppEntity($this);
        }

        return $this->appEntity;
    }

    /**
     * @param shopOrder|array $appEntity
     *
     * @return pocketlistsItemLink
     */
    public function setAppEntity($appEntity)
    {
        $this->appEntity = $appEntity;

        return $this;
    }
}
