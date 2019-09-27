<?php

/**
 * Class pocketlistsNotification
 */
class pocketlistsNotification extends pocketlistsEntity
{
    const TYPE_EMAIL = 1;
    const TYPE_PUSH  = 2;

    const STATUS_PENDING = 0;
    const STATUS_OK      = 1;
    const STATUS_SENDING = 2;
    const STATUS_FAIL    = 99;

    const DIRECTION_EXTERNAL = 'external';
    const DIRECTION_INTERNAL = 'internal';

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $handler;

    /**
     * @var string|DateTime
     */
    private $created_at;

    /**
     * @var string|DateTime
     */
    private $delayed_to;

    /**
     * @var string|DateTime
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
     * @var string
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $direction = self::DIRECTION_EXTERNAL;

    /**
     * @var int|null
     */
    protected $contact_id;

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
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
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
        $this->setData(json_encode($this->content, JSON_UNESCAPED_UNICODE));

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * @param string $identifier
     *
     * @return pocketlistsNotification
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = $identifier;

        return $this;
    }

    /**
     * @return string
     */
    public function getHandler()
    {
        return $this->handler;
    }

    /**
     * @param string $handler
     *
     * @return pocketlistsNotification
     */
    public function setHandler($handler)
    {
        $this->handler = $handler;

        return $this;
    }

    /**
     * @return DateTime|string
     */
    public function getDelayedTo()
    {
        return $this->delayed_to;
    }

    /**
     * @param DateTime|string $delayed_to
     *
     * @return pocketlistsNotification
     */
    public function setDelayedTo($delayed_to)
    {
        $this->delayed_to = $delayed_to;

        return $this;
    }

    /**
     * @return string
     */
    public function getDirection()
    {
        return $this->direction;
    }

    /**
     * @param string $direction
     *
     * @return pocketlistsNotification
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getContactId()
    {
        return $this->contact_id;
    }

    /**
     * @param int|null $contact_id
     *
     * @return pocketlistsNotification
     */
    public function setContactId($contact_id)
    {
        $this->contact_id = $contact_id;

        return $this;
    }
}
