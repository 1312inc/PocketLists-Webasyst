<?php

/**
 * Class pocketlistsEvent
 */
class pocketlistsEvent implements pocketlistsEventInterface
{
    /**
     * @var string
     */
    protected $name = 'base';

    /**
     * @var object
     */
    protected $object;

    /**
     * @var array
     */
    protected $params;

    /**
     * @var mixed
     */
    protected $response;

    /**
     * pocketlistsEvent constructor.
     *
     * @param string $name
     * @param null   $object
     * @param array  $params
     */
    public function __construct($name, $object = null, $params = [])
    {
        $this->name = $name;
        $this
            ->setParams($params)
            ->setObject($object);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param object $object
     *
     * @return pocketlistsEvent
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param array $params
     *
     * @return pocketlistsEvent
     */
    public function setParams($params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     *
     * @return pocketlistsEvent
     */
    public function setResponse($response)
    {
        $this->response = $response;

        return $this;
    }
}
