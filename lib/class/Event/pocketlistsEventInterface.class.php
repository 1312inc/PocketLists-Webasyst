<?php

/**
 * Interface pocketlistsEventInterface
 */
interface pocketlistsEventInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return object
     */
    public function getObject();

    /**
     * @return array
     */
    public function getParams();
}
