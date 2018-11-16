<?php

/**
 * Class pocketlistsItemLink
 */
abstract class pocketlistsItemLink
{
    /**
     * pocketlistsItemLink constructor.
     */
    public function __construct()
    {
        wa($this->getApp());
    }

    abstract function getApp();
}
