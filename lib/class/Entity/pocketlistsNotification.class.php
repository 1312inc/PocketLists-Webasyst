<?php

/**
 * Class pocketlistsNotification
 */
class pocketlistsNotification extends pocketlistsEntity
{
    const TYPE_EMAIL = 1;
    const TYPE_PUSH  = 2;

    const STATUS_PENDING = 0;
    const STATUS_OK = 1;
    const STATUS_FAIL = 2;

    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $created_at;

    /**
     * @var string
     */
    private $sent_at;

    /**
     * @var int
     */
    private $status = self::STATUS_PENDING;

    /**
     * @var string
     */
    private $error;

    /**
     * @var string
     */
    private $data;

    /**
     * @var pocketlistsNotificationContentInterface
     */
    protected $content;

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
     * @return pocketlistsNotification
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return pocketlistsNotification
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @param string $created_at
     *
     * @return pocketlistsNotification
     */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return string
     */
    public function getSentAt()
    {
        return $this->sent_at;
    }

    /**
     * @param string $sent_at
     *
     * @return pocketlistsNotification
     */
    public function setSentAt($sent_at)
    {
        $this->sent_at = $sent_at;

        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return pocketlistsNotification
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param string $error
     *
     * @return pocketlistsNotification
     */
    public function setError($error)
    {
        $this->error = $error;

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
     * @param string $data
     *
     * @return pocketlistsNotification
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return pocketlistsNotificationContentInterface
     * @throws pocketlistsNotImplementedException
     * @throws waException
     */
    public function getContent()
    {
        if ($this->content === null) {
            $this->content = pl2()->getEntityFactory(self::class)->createContentForNotification($this);
        }

        return $this->content;
    }

    /**
     * @param pocketlistsNotificationContentInterface $content
     *
     * @return pocketlistsNotification
     */
    public function setContent(pocketlistsNotificationContentInterface $content)
    {
        $this->content = $content;
        $this->setData($this->content->toJson());

        return $this;
    }
}
