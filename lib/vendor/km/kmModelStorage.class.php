<?php

/**
 * Class kmStorage
 *
 * @property array $data
 */
class kmModelStorage extends kmStorage
{
    /**
     * @var kmStorage
     */
    protected $storageOld;

    protected function init()
    {
        $this->storageOld = new kmStorage();
    }

    /**
     * @param $name
     * @param $value
     */
    public function __set($name, $value)
    {
        // сохраним старое значение
        if (array_key_exists($name, $this->storage)) {
            $this->storageOld[$name] = $this->storage[$name];
        }

//        $method = 'set'.implode('', array_map('ucfirst', explode('_', $name)));
//        if (method_exists($this, $method)) {
//            $this->$method($value);
//        } else {
            $this->storage[$name] = $value;
//        }

        $this->updateKeys();
    }

    /**
     * @return kmStorage
     */
    public function getOld()
    {
        return $this->storageOld;
    }
}