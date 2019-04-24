<?php

/**
 * Class pocketlistsModel
 */
class pocketlistsModel extends waModel
{
    /**
     * pocketlistsModel constructor.
     *
     * @param null $type
     * @param bool $writable
     */
    public function __construct($type = null, $writable = false)
    {
        parent::__construct($type, $writable);

        @$this->exec('SET NAMES utf8mb4');
    }
}
