<?php

/**
 * Interface pocketlistsListenerResponseInterface
 */
interface pocketlistsListenerResponseInterface extends Iterator, Countable
{
    /**
     * @param string $key
     * @param mixed $value
     *
     * @return mixed
     */
    public function addResponseFromListener($key, $value);

    /**
     * @return array
     */
    public function getResponses();
}
