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

    /**
     * @return mixed
     */
    public function getResponse();

    /**
     * @param mixed $response
     *
     * @return pocketlistsEvent
     */
    public function setResponse($response);
}
