<?php

/**
 * Class pocketlistsListDetailsListsDto
 */
class pocketlistsListDetailsListsDto
{
    /**
     * @var int
     */
    public $id;

    /**
     * @var string
     */
    public $nameParsed;

    /**
     * @var string
     */
    public $pocketName;

    /**
     * pocketlistsListDetailsListsDto constructor.
     *
     * @param $id
     * @param $nameParsed
     * @param $pocketName
     */
    public function __construct($id, $nameParsed, $pocketName)
    {
        $this->id = $id;
        $this->nameParsed = $nameParsed;
        $this->pocketName = $pocketName;
    }
}
