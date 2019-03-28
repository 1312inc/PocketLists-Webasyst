<?php

/**
 * Class pocketlistsDataProvider
 */
abstract class pocketlistsDataProvider
{
    /**
     * @var kmModelExt
     */
    protected $model;

    public function insert($entity, $fields = [], $type = 0)
    {
        return $this->getModel()->insert(
            wa()->getConfig()->getHydrator()->extract($entity, $fields),
            $type
        );
    }

    /**
     * @return kmModelExt
     */
    abstract public function getModel();
}