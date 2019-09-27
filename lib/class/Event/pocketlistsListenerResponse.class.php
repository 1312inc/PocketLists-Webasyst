<?php

/**
 * Class pocketlistsListenerResponse
 */
class pocketlistsListenerResponse implements pocketlistsListenerResponseInterface
{
    /**
     * @var array
     */
    private $responses = [];

    /**
     * @var int
     */
    private $index = 0;

    /**
     * @return mixed
     */
    public function current()
    {
        return current($this->responses);
    }

    /**
     * @return mixed|void
     */
    public function next()
    {
        return next($this->responses);
    }

    /**
     * @return int|mixed|string|null
     */
    public function key()
    {
        return key($this->responses);
    }

    /**
     * @return bool
     */
    public function valid()
    {
        return $this->current() !== false;
    }

    public function rewind()
    {
        reset($this->responses);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->responses);
    }

    /**
     * @param string $key
     * @param mixed  $value
     *
     * @return mixed|void
     */
    public function addResponseFromListener($key, $value)
    {
        $this->responses[$key] = $value;
    }

    /**
     * @return array
     */
    public function getResponses()
    {
        return $this->responses;
    }
}
