<?php

/**
 * Class pocketlistsComment
 */
class pocketlistsComment extends pocketlistsEntity
{
    const ALLOWED_DELAY_FOR_DELETE = 86400; // 60 * 60 * 24

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
    private $item_name = '';

    /**
     * @var string
     */
    private $list_name = '';

    /**
     * @var string
     */
    private $list_color = '';

    /**
     * @var int
     */
    private $pocket_id = 0;

    /**
     * @var string
     */
    private $pocket_name = '';

    /**
     * @var int
     */
    private $list_id;

    /**
     * @var int
     */
    private $contact_id;

    /**
     * @var string
     */
    private $comment = '';

    /**
     * @var string
     */
    private $create_datetime;

    /**
     * @var pocketlistsContact
     */
    private $contact;

    /**
     * @var pocketlistsItem
     */
    private $item;

    /**
     * @var bool
     */
    private $recentlyCreated;

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
     * @return pocketlistsComment
     */
    public function setItem(pocketlistsItem $item)
    {
        $this->item = $item;
        $this->item_id = $this->item->getId();

        return $this;
    }

    /**
     * @return bool
     */
    public function canBeDeleted()
    {
        return (time() - strtotime($this->getCreateDatetime()) < self::ALLOWED_DELAY_FOR_DELETE);
    }

    /**
     * @return bool
     */
    public function isMy()
    {
        return $this->getContactId() === pl2()->getUser()->getContact()->getId();
    }

    /**
     * @return bool
     */
    public function isNew()
    {
        return $this->getId() === null;
    }

    /**
     * @param string $lastActivityTime
     *
     * @return $this
     */
    public function setRecentlyCreated($lastActivityTime = '')
    {
        $this->recentlyCreated = strtotime($this->getCreateDatetime()) > strtotime($lastActivityTime);

        return $this;
    }

    /**
     * @return bool
     */
    public function isRecentlyCreated()
    {
        return $this->recentlyCreated;
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
     * @return pocketlistsComment
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
     * @return pocketlistsComment
     */
    public function setItemId($item_id)
    {
        $this->item_id = $item_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getContactId()
    {
        return $this->contact_id;
    }

    /**
     * @param int $contact_id
     *
     * @return pocketlistsComment
     */
    public function setContactId($contact_id)
    {
        $this->contact_id = $contact_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return mixed|string
     * @throws waException
     */
    public function getCommentParsed()
    {
        return pocketlistsNaturalInput::matchLinks($this->getComment());
    }

    /**
     * @param string $comment
     *
     * @return pocketlistsComment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

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
     * @param string $create_datetime
     *
     * @return pocketlistsComment
     */
    public function setCreateDatetime($create_datetime)
    {
        $this->create_datetime = $create_datetime;

        return $this;
    }

    /**
     * @return pocketlistsContact
     * @throws waException
     */
    public function getContact()
    {
        if ($this->getContactId() && $this->contact === null) {
            $this->contact = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId($this->getContactId());
        }

        return $this->contact;
    }

    /**
     * @param pocketlistsContact $contact
     *
     * @return pocketlistsComment
     */
    public function setContact($contact)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * @return string
     */
    public function getItemName()
    {
        return $this->item_name;
    }

    /**
     * @param string $item_name
     *
     * @return pocketlistsComment
     */
    public function setItemName($item_name)
    {
        $this->item_name = $item_name;

        return $this;
    }

    /**
     * @return string
     */
    public function getListName()
    {
        return $this->list_name;
    }

    /**
     * @param string $list_name
     *
     * @return pocketlistsComment
     */
    public function setListName($list_name)
    {
        $this->list_name = $list_name;

        return $this;
    }

    /**
     * @return string
     */
    public function getListColor()
    {
        return $this->list_color;
    }

    /**
     * @param string $list_color
     *
     * @return pocketlistsComment
     */
    public function setListColor($list_color)
    {
        $this->list_color = $list_color;

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
     * @return pocketlistsComment
     */
    public function setPocketId($pocket_id)
    {
        $this->pocket_id = $pocket_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getPocketName()
    {
        return $this->pocket_name;
    }

    /**
     * @param string $pocket_name
     *
     * @return pocketlistsComment
     */
    public function setPocketName($pocket_name)
    {
        $this->pocket_name = $pocket_name;

        return $this;
    }

    /**
     * @return int
     */
    public function getListId()
    {
        return $this->list_id;
    }

    /**
     * @param int $list_id
     *
     * @return pocketlistsComment
     */
    public function setListId($list_id)
    {
        $this->list_id = $list_id;

        return $this;
    }
}
