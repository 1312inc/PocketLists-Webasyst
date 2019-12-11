<?php

/**
 * Class pocketlistsProPluginCreateItemActionListDto
 */
class pocketlistsProPluginCreateItemActionListDto
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
     * pocketlistsProPluginCreateItemActionListDto constructor.
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
