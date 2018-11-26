<?php

/**
 * Class pocketlistsFactory
 */
abstract class pocketlistsFactory
{
    /**
     * @var kmModelExt
     */
    protected $model;

    /**
     * @return kmModelExt
     */
    abstract protected function getModel();
}