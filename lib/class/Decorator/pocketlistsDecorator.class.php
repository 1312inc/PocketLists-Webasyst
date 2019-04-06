<?php

/**
 * Class pocketlistsDecorator
 */
class pocketlistsDecorator
{
    /**
     * @var pocketlistsEntity
     */
    protected $object;

    /**
     * pocketlistsDecorator constructor.
     *
     * @param pocketlistsEntity $object
     */
    public function __construct(pocketlistsEntity $object)
    {
        $this->object = $object;
    }

    /**
     * @param pocketlistsEntity[] $objects
     *
     * @return array
     */
    public static function decorate(array $objects)
    {
        $decorated = [];
        foreach ($objects as $object) {
            $decorated[] = new self($object);
        }

        return $decorated;
    }

    /**
     * @return pocketlistsEntity
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param $method
     * @param $args
     *
     * @return mixed
     * @throws waException
     */
    public function __call($method, $args)
    {
        if (method_exists($this->object, $method)) {
            return call_user_func_array([$this->object, $method], $args);
        }

        throw new waException(
            'Undefined method - '.get_class($this->object).'::'.$method
        );
    }
}
