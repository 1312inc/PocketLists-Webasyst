<?php

/**
 * Class pocketlistsCreateItemActionListDto
 */
class pocketlistsCreateItemActionListDto
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $pocketName;

    /**
     * pocketlistsCreateItemActionListDto constructor.
     *
     * @param $listId
     * @param $name
     * @param $pocketName
     */
    public function __construct($listId, $name, $pocketName)
    {
        $this->id = $listId;
        $this->name = $name;
        $this->pocketName = $pocketName;
    }
}