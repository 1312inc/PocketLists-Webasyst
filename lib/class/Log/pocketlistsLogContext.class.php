<?php


/**
 * Class pocketlistsLogContext
 */
class pocketlistsLogContext
{
    const POCKET_ENTITY     = 'pocket';
    const LIST_ENTITY       = 'list';
    const ITEM_ENTITY       = 'item';
    const COMMENT_ENTITY    = 'comment';
    const ATTACHMENT_ENTITY = 'attachment';
    const LOCATION_ENTITY   = 'location';

    const ITEM_FIELDS_TO_EXTRACT = [
        'status',
        'contact_id',
        'priority',
        'calc_priority',
        'create_datetime',
        'due_date',
        'due_datetime',
        'location_id',
        'assigned_contact_id',
        'repeat',
    ];

    const ITEM_MORE_INFO_APP_LINKS = 1;
    const ITEM_MORE_INFO = self::ITEM_MORE_INFO_APP_LINKS;

    /**
     * @var pocketlistsPocket
     */
    private $pocket;

    /**
     * @var pocketlistsList
     */
    private $list;

    /**
     * @var pocketlistsItem
     */
    private $item;

    /**
     * @var pocketlistsComment
     */
    private $comment;

    /**
     * @var pocketlistsAttachment
     */
    private $attachment;

    /**
     * @var pocketlistsLocation
     */
    private $location;

    /**
     * @var array
     */
    private $params = [];

    /**
     * @var pocketlistsEntity|int
     */
    private $additional;

    /**
     * @var string
     */
    private $action;

    /**
     * @return int|pocketlistsEntity
     */
    public function getAdditional()
    {
        return $this->additional;
    }

    /**
     * @param int|pocketlistsEntity|pocketlistsContact $additional
     *
     * @return pocketlistsLogContext
     */
    public function setAdditional($additional)
    {
        $this->additional = $additional;

        return $this;
    }

    /**
     * @param pocketlistsEntity $entity
     *
     * @throws pocketlistsForbiddenException
     */
    public function addEntity($entity)
    {
        $type = $this->getEntityMapping($entity);
        if ($this->{$type} === null) {
            $this->{$type} = $entity;
        }
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     *
     * @return pocketlistsLogContext
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return pocketlistsEntity[]
     */
    public function getEntities()
    {
        return array_filter(
            [
                self::POCKET_ENTITY     => $this->pocket,
                self::LIST_ENTITY       => $this->list,
                self::ITEM_ENTITY       => $this->item,
                self::COMMENT_ENTITY    => $this->comment,
                self::ATTACHMENT_ENTITY => $this->attachment,
                self::LOCATION_ENTITY   => $this->location,
            ]
        );
    }

    /**
     * @param $name
     *
     * @return pocketlistsPocket|pocketlistsList|pocketlistsItem|pocketlistsAttachment|pocketlistsComment|pocketlistsLocation
     * @throws pocketlistsLogicException
     */
    public function getEntity($name)
    {
        if (!property_exists($this, $name)) {
            throw new pocketlistsLogicException(sprintf_wp('No %s entity object in log context', $name));
        }

        if ($this->{$name} === null) {
            throw new pocketlistsLogicException(sprintf_wp('There should be %s entity in log context', $name));
        }

        return $this->{$name};
    }

    /**
     * @param array $param
     *
     * @return $this
     */
    public function addParam(array $param)
    {
        $this->params = array_merge_recursive($this->params, $param);

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
     * @return pocketlistsLogContext
     */
    public function setPocket(pocketlistsPocket $pocket)
    {
        $this->pocket = $pocket;

        return $this;
    }

    /**
     * @return pocketlistsList
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @param pocketlistsList $list
     *
     * @return pocketlistsLogContext
     * @throws waException
     */
    public function setList(pocketlistsList $list)
    {
        if ($this->list === null) {
            $this->list = $list;

            $this->setPocketByList();
        }

        return $this;
    }

    /**
     * @return pocketlistsItem
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * @param pocketlistsItem $item
     *
     * @param int             $moreInfo
     *
     * @return pocketlistsLogContext
     * @throws waException
     */
    public function setItem(pocketlistsItem $item, $moreInfo = 0)
    {
        if ($this->item === null) {
            $this->item = $item;

            if ($moreInfo) {
                try {
                    /** @var pocketlistsItemLink $link */
                    foreach ($item->getAppLinks() as $link) {
                        $this->setParams([
                            'item' => [
                                $link->getApp() => [
                                    'link'        => $link->getAppLink()->getLinkUrl($link),
                                    'entity_id'   => $link->getEntityId(),
                                    'entity_num'  => $link->getAppLink()->getEntityNum($link),
                                    'entity_type' => $link->getEntityType(),
                                    'app_name'    => $link->getAppLink()->getName(),
                                    'app_icon'    => $link->getAppLink()->getAppIcon(),
                                ],
                            ],
                        ]);
                    }
                } catch (waException $ex) {}
            }

            $this->setListByItem();
        }

        return $this;
    }

    /**
     * @return pocketlistsComment
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param pocketlistsComment $comment
     *
     * @return pocketlistsLogContext
     * @throws waException
     */
    public function setComment(pocketlistsComment $comment)
    {
        if ($this->comment === null) {
            $this->comment = $comment;

            $this->setItemByComment();
        }

        return $this;
    }

    /**
     * @return pocketlistsAttachment
     */
    public function getAttachment()
    {
        return $this->attachment;
    }

    /**
     * @param pocketlistsAttachment $attachment
     *
     * @return pocketlistsLogContext
     * @throws waException
     */
    public function setAttachment(pocketlistsAttachment $attachment)
    {
        if ($this->attachment === null) {
            $this->attachment = $attachment;

            $this->setItemByAttachment();
        }

        return $this;
    }

    /**
     * @return pocketlistsLocation
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @param pocketlistsLocation $location
     *
     * @return pocketlistsLogContext
     */
    public function setLocation(pocketlistsLocation $location)
    {
        $this->location = $location;

        return $this;
    }

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     *
     * @return pocketlistsLogContext
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * @throws waException
     */
    protected function setListByItem()
    {
        if ($this->list === null && $this->item->getListId()) {
            $this->setList($this->item->getList());
        }
    }

    /**
     * @throws waException
     */
    protected function setItemByComment()
    {
        if ($this->item === null && $this->comment->getItemId()) {
            $this->setItem($this->comment->getItem());
        }
    }

    /**
     * @throws waException
     */
    protected function setItemByAttachment()
    {
        if ($this->item === null && $this->attachment->getItemId()) {
            $this->setItem($this->attachment->getItem());
        }
    }

    /**
     * @throws waException
     */
    protected function setPocketByList()
    {
        if ($this->pocket === null && $this->list->getPocketId()) {
            $this->setPocket($this->list->getPocket());
        }
    }

    /**
     * @param pocketlistsEntity $entity
     *
     * @return string
     * @throws pocketlistsForbiddenException
     */
    private function getEntityMapping(pocketlistsEntity $entity)
    {
        $class = get_class($entity);

        switch ($class) {
            case pocketlistsAttachment::class:
                return self::ATTACHMENT_ENTITY;

            case pocketlistsPocket::class:
                return self::POCKET_ENTITY;

            case pocketlistsList::class:
                return self::LIST_ENTITY;

            case pocketlistsItem::class:
                return self::ITEM_ENTITY;

            case pocketlistsComment::class:
                return self::COMMENT_ENTITY;
        }

        throw new pocketlistsForbiddenException(sprintf_wp('No log mapping for %s', $class));
    }
}
