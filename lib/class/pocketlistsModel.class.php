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

        try {
            $this->exec('set names utf8mb4');
        } catch (Exception $ex) {
            waLog::log('PLEASE UPDATE YOUR MYSQL DATABASE. ' . $ex->getMessage());
        }
    }
}
