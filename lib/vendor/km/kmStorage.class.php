<?php

/**
 * Class kmStorage
 *
 * @property array $data
 */
class kmStorage implements ArrayAccess, Iterator
{
    /**
     * @var array
     */
    protected $storage;

    /**
     * @var int
     */
    protected $position = 0;
    /**
     * @var array
     */
    protected $keys = [];


    /**
     * kmStorage constructor.
     *
     * @param $data array
     */
    public function __construct($data = [])
    {
        $this->position = 0;

        $this->fillData($data);
        $this->init();

        $this->updateKeys();
    }

    protected function init()
    {

    }

    protected function updateKeys()
    {
        $this->keys = array_keys($this->storage);
    }

    public function fillData($data)
    {
        $this->storage = $data;

        $this->updateKeys();
    }

    public function updateData($data)
    {
        $this->storage += $data;

        $this->updateKeys();
    }

    public function &storageData()
    {
        return $this->storage;
    }

    /**
     * @param $name
     *
     * @return mixed
     */
    public function &__get($name)
    {
//        $method = 'get'.implode('', array_map('ucfirst', explode('_', $name)));
//        if (method_exists($this, $method)) {
//            $value = $this->$method();
//
//            return $value;
//        }

        if (array_key_exists($name, $this->storage)) {
            return $this->storage[$name];
        }

        $null = null;

        return $null;
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
//        $method = 'set'.implode('', array_map('ucfirst', explode('_', $name)));
//        if (method_exists($this, $method)) {
//            $this->$method($value);
//        } else {
            $this->storage[$name] = $value;
//        }

        if (!in_array($name, $this->keys, true)) {
            $this->keys[] = $name;
        }
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->storage);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    public function offsetSet($offset, $value)
    {
        if ($offset === null) {
            $this->storage['nokey'][] = $value;
        } else {
            $this->__set($offset, $value);
        }
    }

    /**
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return array_key_exists($offset, $this->storage);
    }

    public function offsetUnset($offset)
    {
        unset($this->storage[$offset]);
    }

    public function count()
    {
        return count($this->storage);
    }

    public function clear()
    {
        return $this->storage = [];
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->storage[$this->keys[$this->position]];
    }

    public function key()
    {
        return $this->keys[$this->position];
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return array_key_exists($this->position, $this->keys)
            && array_key_exists($this->keys[$this->position], $this->storage);
    }
}