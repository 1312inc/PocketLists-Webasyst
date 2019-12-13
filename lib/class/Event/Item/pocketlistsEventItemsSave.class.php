<?php

/**
 * Class pocketlistsEventItemCreate
 */
class pocketlistsEventItemsSave extends pocketlistsEvent
{
    /**
     * @var pocketlistsItem[]
     */
    private $items;

    /**
     * @var pocketlistsList|null
     */
    private $list;

    /**
     * @var int
     */
    private $assignContactId;

    /**
     * @var int
     */
    private $oldAssignContactId;

    /**
     * pocketlistsEventItemsCreate constructor.
     *
     * @param string            $name
     * @param pocketlistsItem[] $items
     * @param array             $params
     */
    public function __construct($name, $items = null, $params = [])
    {
        parent::__construct($name, null, $params);

        $this->items = is_array($items) ? $items : [$items];
        $this->list = ifset($params, 'list', null);
        $this->assignContactId = ifset($params, 'assign_contact_id', 0);
        $this->oldAssignContactId = ifset($params, 'old_assign_contact_id', 0);
    }

    /**
     * @return pocketlistsItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * @return pocketlistsList|null
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @return int
     */
    public function getAssignContactId()
    {
        return $this->assignContactId;
    }

    /**
     * @return int
     */
    public function getOldAssignContactId()
    {
        return $this->oldAssignContactId;
    }
}
