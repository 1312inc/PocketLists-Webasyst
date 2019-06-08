<?php

/**
 * Class pocketlistsNullList
 */
class pocketlistsNullList extends pocketlistsList
{
    /**
     * @return null
     */
    public function getId()
    {
        return null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'NULL list';
    }

    /**
     * @return pocketlistsPocket
     */
    public function getPocket()
    {
        return new pocketlistsPocket();
    }
}
