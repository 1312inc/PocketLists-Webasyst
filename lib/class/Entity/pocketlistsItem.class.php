<?php

class pocketlistsItem extends pocketlistsEntity
{
    const PRIORITY_NORM       = 0;
    const PRIORITY_GREEN      = 1;
    const PRIORITY_YELLOW     = 2;
    const PRIORITY_RED        = 3;
    const PRIORITY_BLACK      = 4;
    const PRIORITY_BURNINHELL = 5;

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
    protected $name;

    /**
     * @var pocketlistsList
     */
    protected $list;

    /**
     * @var int
     */
    protected $status;

    /**
     * @var bool
     */
    private $has_children;

    /**
     * @var int
     */
    private $priority = self::PRIORITY_NORM;

    /**
     * @var int
     */
    private $calc_priority;

    /**
     * @var int|null
     */
    private $parent_id;

    /**
     * @var int|null
     */
    private $list_id;

    /**
     * @var string
     */
    private $note;

    /**
     * @var DateTime|null
     */
    private $due_date;

    /**
     * @var DateTime|null
     */
    private $due_datetime;

    /**
     * @var int|null
     */
    private $location_id;

    /**
     * @var float|null
     */
    private $amount;

    /**
     * @var string|null
     */
    private $currency_iso3;

    /**
     * @var int|null
     */
    private $assigned_contact_id;

    /**
     * @var string|null
     */
    private $repeat;

    /**
     * @var int|null
     */
    private $key_list_id;

    /**
     * @var int
     */
    private $comments_count = 0;

    /**
     * @var int
     */
    private $attachments_count = 0;

    /**
     * @var pocketlistsContact
     */
    private $contact;

    /**
     * @var pocketlistsContact
     */
    private $assignContact;

    /**
     * @var pocketlistsContact
     */
    private $completeContact;

    /**
     * @throws waException
     */
    public function afterHydrate()
    {
        if ($this->getAttachmentsCount()) {
            //todo: надо ли сразу загружать?
        }

        $this->contact = new pocketlistsContact(new waContact($this->getContactId()));

        if ($this->getAssignedContactId()) {
            $this->assignContact = new pocketlistsContact(new waContact($this->getAssignedContactId()));
        }

        if ($this->getCompleteContactId()) {
            $this->completeContact = new pocketlistsContact(new waContact($this->getCompleteContactId()));
        }

        $this->recalculatePriorityData();

//        if (!$edit) {
//            $this->prepareOutput($item);
//        }
    }

    /**
     * @return pocketlistsContact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * @return pocketlistsContact
     */
    public function getAssignContact()
    {
        return $this->assignContact;
    }

    /**
     * @return pocketlistsContact
     */
    public function getCompleteContact()
    {
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
     * @return $this
     */
    public function recalculatePriorityData()
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
     * @return int
     */
    public function getAttachmentsCount()
    {
        return $this->attachments_count;
    }

    /**
     * @param int $attachments_count
     *
     * @return pocketlistsItem
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
     * @return pocketlistsItem
     */
    public function setCommentsCount($comments_count)
    {
        $this->comments_count = $comments_count;

        return $this;
    }

    /**
     * @return array
     */
    public function getDbFields()
    {
        return [
            '',
        ];
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
     * @return pocketlistsItem
     */
    public function setList($list)
    {
        $this->list = $list;

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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
     */
    public function setListId($list_id)
    {
        $this->list_id = $list_id;

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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
     */
    public function setCalcPriority($calc_priority)
    {
        $this->calc_priority = $calc_priority;

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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
     */
    public function setDueDate($due_date = null)
    {
        $this->due_date = !empty($due_date) ? $due_date : null;

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
     * @return pocketlistsItem
     */
    public function setDueDatetime($due_datetime = null)
    {
        $this->due_datetime = !empty($due_datetime) ? $due_datetime : null;

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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
     */
    public function setAssignedContactId($assigned_contact_id)
    {
        $this->assigned_contact_id = $assigned_contact_id;

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
     * @return pocketlistsItem
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
     * @return pocketlistsItem
     */
    public function setKeyListId($key_list_id)
    {
        $this->key_list_id = $key_list_id;

        return $this;
    }
}
