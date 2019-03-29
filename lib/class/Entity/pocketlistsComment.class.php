<?php

/**
 * Class pocketlistsComment
 */
class pocketlistsComment extends pocketlistsEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $item_id;

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
     */
    public function getContact()
    {
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
}
