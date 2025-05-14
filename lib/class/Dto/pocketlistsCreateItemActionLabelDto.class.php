<?php

/**
 * Class pocketlistsCreateItemActionListDto
 */
class pocketlistsCreateItemActionLabelDto
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
    public $color;

    /**
     * pocketlistsCreateItemActionLabelDto constructor.
     *
     * @param $listId
     * @param $name
     * @param $color
     */
    public function __construct($listId, $name, $color)
    {
        $this->id = $listId;
        $this->name = $name;
        $this->color = $color;
    }
}
