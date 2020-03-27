<?php

/**
 * Class pocketlistsItem
 */
class pocketlistsItem extends pocketlistsEntity
{
    const PRIORITY_NORM       = 0;
    const PRIORITY_GREEN      = 1;
    const PRIORITY_YELLOW     = 2;
    const PRIORITY_RED        = 3;
    const PRIORITY_BLACK      = 4;
    const PRIORITY_BURNINHELL = 5;

    const STATUS_UNDONE = 0;
    const STATUS_DONE   = 1;

    const REPEAT_DEFAULT = 0;

    /**
     * @var int
     */
    protected $id;

    /**
     * @var int
     */
    protected $contact_id;

    /**
     * @var int
     */
    protected $sort;

    /**
     * @var DateTime|null
     */
    protected $create_datetime;

    /**
     * @var DateTime|null
     */
    protected $update_datetime;

    /**
     * @var DateTime|null
     */
    protected $complete_datetime;

    /**
     * @var int|null
     */
    protected $complete_contact_id;

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var pocketlistsList
     */
    protected $list;

    /**
     * @var int
     */
    protected $status = 0;

    /**
     * @var bool
     */
    protected $has_children = 0;

    /**
     * @var int
     */
    protected $priority = self::PRIORITY_NORM;

    /**
     * @var int
     */
    protected $calc_priority;

    /**
     * @var int|null
     */
    protected $parent_id;

    /**
     * @var int|null
     */
    protected $list_id;

    /**
     * @var string
     */
    protected $note;

    /**
     * @var DateTime|null
     */
    protected $due_date;

    /**
     * @var DateTime|null
     */
    protected $due_datetime;

    /**
     * @var int|null
     */
    protected $location_id;

    /**
     * @var float|null
     */
    protected $amount = 0;

    /**
     * @var string|null
     */
    protected $currency_iso3;

    /**
     * @var int|null
     */
    protected $assigned_contact_id;

    /**
     * @var string|null
     */
    protected $repeat = self::REPEAT_DEFAULT;

    /**
     * @var int|null
     */
    protected $key_list_id;

    /**
     * @var int
     */
    private $comments_count = 0;

    /**
     * @var pocketlistsComment[]
     */
    private $comments;

    /**
     * @var int
     */
    protected $attachments_count = 0;

    /**
     * @var pocketlistsContact
     */
    protected $contact;

    /**
     * @var pocketlistsContact
     */
    private $assignedContact;

    /**
     * @var pocketlistsContact
     */
    private $completeContact;

    /**
     * @var int
     */
    protected $linkedEntitiesCount = 0;

    /**
     * @var pocketlistsItemLinkModel[]
     */
    protected $linkedEntities;

    /**
     * @var bool
     */
    protected $favorite;

    /**
     * @var pocketlistsItem[]
     */
    private $childs = [];

    /**
     * @var pocketlistsAttachment[]
     */
    protected $attachments;

    /**
     * @var pocketlistsItemLink[]
     */
    private $appLinks;

    /**
     * @var pocketlistsPocket|null
     */
    private $pocket;

    /**
     * @return pocketlistsComment[]
     * @throws waException
     */
    public function getComments()
    {
        if ($this->comments === null) {
            if ($this->getCommentsCount()) {
                /** @var pocketlistsCommentFactory $commentFactory */
                $commentFactory = pl2()->getEntityFactory(pocketlistsComment::class);
                $this->comments = $commentFactory->findForItem($this);
            } else {
                $this->comments = [];
            }
        }

        return $this->comments;
    }

    /**
     * @param pocketlistsComment[] $comments
     *
     * @return $this
     */
    public function setComments($comments = null)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return $thisLink[]
     * @throws waException
     */
    public function getAppLinks()
    {
        if ($this->appLinks === null) {
            $this->appLinks = pl2()->getEntityFactory(pocketlistsItemLink::class)->findForItem($this);
        }

        return $this->appLinks;
    }

    /**
     * @return int
     */
    public function getAppLinksCount()
    {
        return is_array($this->appLinks) ? count($this->appLinks) : 0;
    }

    /**
     * @param pocketlistsItemLink[] $appLinks
     *
     * @return $this
     */
    public function setAppLinks($appLinks)
    {
        $this->appLinks = $appLinks;

        return $this;
    }

    /**
     * @param pocketlistsItemLink $appLink
     *
     * @return $this
     */
    public function addAppLinks(pocketlistsItemLink $appLink)
    {
        $this->appLinks[] = $appLink;
        $this->linkedEntitiesCount++;

        return $this;
    }

    /**
     * @return pocketlistsAttachment[]
     * @throws waException
     */
    public function getAttachments()
    {
        if ($this->getAttachmentsCount() === 0) {
            return [];
        }

        if ($this->attachments === null) {
            $this->attachments = pl2()->getEntityFactory(pocketlistsAttachment::class)->findByFields(
                'item_id',
                $this->getId(),
                true
            );
        }

        return $this->attachments;
    }

    /**
     * @param pocketlistsAttachment[]|null $attachments
     *
     * @return $this
     */
    public function setAttachments($attachments = null)
    {
        $this->attachments = $attachments;
        $this->attachments_count = is_array($attachments) ? count($attachments) : 0;

        return $this;
    }

    /**
     * @return pocketlistsContact
     * @throws waException
     */
    public function getContact()
    {
        if ($this->contact === null) {
            $this->contact = pl2()->getEntityFactory(pocketlistsContact::class)
                ->createNewWithId($this->getContactId());
        }

        return $this->contact;
    }

    /**
     * @return pocketlistsContact|null
     * @throws waException
     */
    public function getAssignedContact()
    {
        if ($this->assignedContact === null && $this->getAssignedContactId()) {
            $this->assignedContact = pl2()->getEntityFactory(pocketlistsContact::class)
                ->createNewWithId($this->getAssignedContactId());

        }

        return $this->assignedContact;
    }

    /**
     * @return $this[]
     */
    public function getChilds()
    {
        return $this->childs;
    }

    /**
     * @param pocketlistsItem[] $childs
     *
     * @return $this
     */
    public function setChilds($childs)
    {
        $this->childs = $childs;

        return $this;
    }

    /**
     * @return pocketlistsContact|null
     * @throws waException
     */
    public function getCompleteContact()
    {
        if ($this->completeContact === null && $this->getCompleteContactId()) {
            $this->completeContact = pl2()->getEntityFactory(pocketlistsContact::class)
                ->createNewWithId($this->getCompleteContactId());
        }

        return $this->completeContact;
    }

    /**
     * @return string
     */
    public function getAgeTime()
    {
        $age_time = time() - max(
                $this->getUpdateDatetime() ? strtotime($this->getUpdateDatetime()) : 0,
                strtotime($this->getCreateDatetime())
            );

        return $age_time < 1 ? '' : pocketlistsHelper::getDatetimeBySeconds($age_time);;
    }

    /**
     * @return int
     */
    public function getLinkedEntitiesCount()
    {
        return $this->linkedEntitiesCount;
    }

    /**
     * @param int $linkedEntitiesCount
     *
     * @return $this
     */
    public function setLinkedEntitiesCount($linkedEntitiesCount)
    {
        $this->linkedEntitiesCount = $linkedEntitiesCount;

        return $this;
    }

    /**
     * @return $thisLinkModel[]
     * @throws waException
     */
    public function getLinkedEntities()
    {
        if ($this->linkedEntities === null && $this->getLinkedEntitiesCount() > 0) {
            /** @var pocketlistsItemLinkFactory $factory */
            $factory = wa(pocketlistsHelper::APP_ID)->getConfig()->getEntityFactory(pocketlistsItemLink::class);
            $this->linkedEntities = $factory->findForItem($this) ?: [];
        }

        return $this->linkedEntities;
    }

    /**
     * @return bool
     */
    public function isFavorite()
    {
        return $this->favorite;
    }

    /**
     * @param bool $favorite
     *
     * @return $this
     */
    public function setFavorite($favorite)
    {
        $this->favorite = $favorite;

        return $this;
    }

    /**
     * @param pocketlistsContact $contact
     *
     * @return $this
     * @throws waException
     */
    public function makeFavorite(pocketlistsContact $contact)
    {
        if (pl2()->getModel('pocketlistsUserFavorites')->insert(
            ['item_id' => $this->getId(), 'contact_id' => $contact->getId()],
            waModel::INSERT_ON_DUPLICATE_KEY_UPDATE
        )) {
            $this->setFavorite(true);
        }

        return $this;
    }

    /**
     * @param pocketlistsContact $contact
     *
     * @return $this
     * @throws waException
     */
    public function removeFavorite(pocketlistsContact $contact)
    {
        if (pl2()->getModel('pocketlistsUserFavorites')->deleteByField(
            ['item_id' => $this->getId(), 'contact_id' => $contact->getId()]
        )) {
            $this->setFavorite(false);
        }

        return $this;
    }

    /**
     * @return int
     */
    public function getAttachmentsCount()
    {
        return $this->attachments_count;
    }

    /**
     * @param int $attachments_count
     *
     * @return $this
     */
    public function setAttachmentsCount($attachments_count)
    {
        $this->attachments_count = $attachments_count;

        return $this;
    }

    /**
     * @return int
     */
    public function getCommentsCount()
    {
        return $this->comments_count;
    }

    /**
     * @param int $comments_count
     *
     * @return $this
     */
    public function setCommentsCount($comments_count)
    {
        $this->comments_count = $comments_count;

        return $this;
    }

    /**
     * @return pocketlistsList|pocketlistsNullList
     * @throws waException
     */
    public function getList()
    {
        if ($this->list === null) {
            /** @var pocketlistsListFactory $factory */
            $factory = pl2()->getEntityFactory(pocketlistsList::class);

            if ($this->getListId()) {
                $this->list = $factory->findById($this->getListId());
            } else {
                $this->list = $factory->createNewNullList();
            }
        }

        return $this->list;
    }

    /**
     * @param pocketlistsList $list
     *
     * @return $this
     */
    public function setList(pocketlistsList $list)
    {
        $this->list = $list;
        $this->setListId($this->list->getId());

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
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getListId()
    {
        return $this->list_id;
    }

    /**
     * @param int|null $list_id
     *
     * @return $this
     */
    public function setListId($list_id)
    {
        $this->list_id = $list_id ?: null;

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
     * @return $this
     */
    public function setContactId($contact_id)
    {
        $this->contact_id = $contact_id;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getParentId()
    {
        return $this->parent_id;
    }

    /**
     * @param int|null $parent_id
     *
     * @return $this
     */
    public function setParentId($parent_id)
    {
        $this->parent_id = $parent_id;

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
     * @return $this
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return bool
     */
    public function isHasChildren()
    {
        return $this->has_children;
    }

    /**
     * @param bool $has_children
     *
     * @return $this
     */
    public function setHasChildren($has_children)
    {
        $this->has_children = $has_children;

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
     * @return $this
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @param int $priority
     *
     * @return $this
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * @return int
     */
    public function getCalcPriority()
    {
        return $this->calc_priority;
    }

    /**
     * @param int $calc_priority
     *
     * @return $this
     */
    public function setCalcPriority($calc_priority)
    {
        $this->calc_priority = $calc_priority;

        return $this;
    }

    /**
     * @return $this
     */
    public function recalculatePriority()
    {
        $this->setCalcPriority(
            max(
                pocketlistsHelper::calcPriorityOnDueDate($this->getDueDate(), $this->getDueDatetime()),
                $this->getPriority()
            )
        );

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCreateDatetime()
    {
        return $this->create_datetime;
    }

    /**
     * @param DateTime|null $create_datetime
     *
     * @return $this
     */
    public function setCreateDatetime($create_datetime)
    {
        $this->create_datetime = $create_datetime;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdateDatetime()
    {
        return $this->update_datetime;
    }

    /**
     * @param DateTime|null $update_datetime
     *
     * @return $this
     */
    public function setUpdateDatetime($update_datetime)
    {
        $this->update_datetime = $update_datetime;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getCompleteDatetime()
    {
        return $this->complete_datetime;
    }

    /**
     * @param DateTime|null $complete_datetime
     *
     * @return $this
     */
    public function setCompleteDatetime($complete_datetime)
    {
        $this->complete_datetime = $complete_datetime;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getCompleteContactId()
    {
        return $this->complete_contact_id;
    }

    /**
     * @param int|null $complete_contact_id
     *
     * @return $this
     */
    public function setCompleteContactId($complete_contact_id)
    {
        $this->complete_contact_id = $complete_contact_id;

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
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     *
     * @return $this
     */
    public function setNote($note)
    {
        $this->note = $note;

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDueDate()
    {
        return $this->due_date;
    }

    /**
     * @param DateTime|null $due_date
     *
     * @return $this
     */
    public function setDueDate($due_date = null)
    {
        $this->due_date = !empty($due_date) ? $due_date : null;
        $this->recalculatePriority();

        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDueDatetime()
    {
        return $this->due_datetime;
    }

    /**
     * @param DateTime|null $due_datetime
     *
     * @return $this
     */
    public function setDueDatetime($due_datetime = null)
    {
        $this->due_datetime = !empty($due_datetime) ? $due_datetime : null;
        $this->recalculatePriority();

        return $this;
    }

    /**
     * @return int|null
     */
    public function getLocationId()
    {
        return $this->location_id;
    }

    /**
     * @param int|null $location_id
     *
     * @return $this
     */
    public function setLocationId($location_id)
    {
        $this->location_id = $location_id;

        return $this;
    }

    /**
     * @return float|null
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @param float|null $amount
     *
     * @return $this
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCurrencyIso3()
    {
        return $this->currency_iso3;
    }

    /**
     * @param string|null $currency_iso3
     *
     * @return $this
     */
    public function setCurrencyIso3($currency_iso3)
    {
        $this->currency_iso3 = $currency_iso3;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getAssignedContactId()
    {
        return $this->assigned_contact_id;
    }

    /**
     * @param int|null $assigned_contact_id
     *
     * @return $this
     */
    public function setAssignedContactId($assigned_contact_id = null)
    {
        $this->assigned_contact_id = !empty($assigned_contact_id) ? (int)$assigned_contact_id : null;

        return $this;
    }

    /**
     * @param pocketlistsContact $contact
     *
     * @return $this
     */
    public function setContact(pocketlistsContact $contact)
    {
        $this->contact = $contact;
        $this->setContactId($this->contact->getId());

        return $this;
    }

    /**
     * @param pocketlistsContact|null $assignedContact
     *
     * @return $this
     */
    public function setAssignedContact(pocketlistsContact $assignedContact = null)
    {
        $this->assignedContact = $assignedContact;
        if ($this->assignedContact) {
            $this->setAssignedContactId($this->assignedContact->getId());
        }

        return $this;
    }

    /**
     * @param pocketlistsContact|null $completeContact
     *
     * @return $this
     */
    public function setCompleteContact(pocketlistsContact $completeContact = null)
    {
        $this->completeContact = $completeContact;
        if ($this->completeContact) {
            $this->setCompleteContactId($this->completeContact->getId());
        }

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRepeat()
    {
        return $this->repeat;
    }

    /**
     * @param string|null $repeat
     *
     * @return $this
     */
    public function setRepeat($repeat)
    {
        $this->repeat = $repeat;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getKeyListId()
    {
        return $this->key_list_id;
    }

    /**
     * @param int|null $key_list_id
     *
     * @return $this
     */
    public function setKeyListId($key_list_id)
    {
        $this->key_list_id = $key_list_id;

        return $this;
    }

    /**
     * @return bool
     */
    public function isDone()
    {
        return $this->getStatus() == self::STATUS_DONE && $this->getCompleteDatetime();
    }

    /**
     * @param string $type
     *
     * @return string
     */
    public function getCssClass($type)
    {
        return pocketlistsViewHelper::getPriorityCssClass($this->getCalcPriority(), $type);
    }

    /**
     * @return bool
     * @throws waException
     */
    public function isAssignedtoSomeone()
    {
        return $this->getAssignedContact() && $this->getAssignedContactId() != $this->getContactId();
    }

    /**
     * @return $this
     */
    public function setDone()
    {
        $this
            ->setStatus(1)
            ->setCompleteDatetime(date("Y-m-d H:i:s"))
            ->setCompleteContactId(wa()->getUser()->getId());

        return $this;
    }

    /**
     * @return $this
     */
    public function setUndone()
    {
        $this
            ->setStatus(0)
            ->setCompleteDatetime('')
            ->setCompleteContactId(null);

        return $this;
    }

    /**
     * @param bool $encode
     *
     * @return string
     * @throws waException
     */
    public function getNameParsed($encode = true)
    {
        return pocketlistsNaturalInput::matchLinks($this->getName(), $encode);
    }

    /**
     * @param bool $encode
     *
     * @return string
     * @throws waException
     */
    public function getNoteParsed($encode = true)
    {
        return pocketlistsNaturalInput::matchLinks($this->getNote(), true);
    }

    /**
     * @return pocketlistsPocket|null
     * @throws waException
     */
    public function getPocket()
    {
        if ($this->pocket === null && $this->getListId()) {
            $this->pocket = pl2()->getEntityFactory(pocketlistsPocket::class)->findByListId($this->getListId());
        }

        return $this->pocket;
    }
}
