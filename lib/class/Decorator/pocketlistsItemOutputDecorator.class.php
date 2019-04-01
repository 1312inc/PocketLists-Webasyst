<?php

/**
 * Class pocketlistsItemOutputDecorator
 */
class pocketlistsItemOutputDecorator extends pocketlistsDecorator
{
    /**
     * @var pocketlistsItem
     */
    protected $object;

    /**
     * @return mixed|string
     * @throws waException
     */
    public function getName()
    {
        return pocketlistsNaturalInput::matchLinks($this->object->getName());
    }

    /**
     * @return mixed|string
     * @throws waException
     */
    public function getNote()
    {
        return pocketlistsNaturalInput::matchLinks($this->object->getNote());
    }
}
