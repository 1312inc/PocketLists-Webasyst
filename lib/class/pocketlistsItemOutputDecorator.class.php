<?php

/**
 * Class pocketlistsItemOutputDecorator
 */
class pocketlistsItemOutputDecorator
{
    /**
     * @var pocketlistsItem
     */
    private $item;

    /**
     * pocketlistsItemOutputDecorator constructor.
     *
     * @param pocketlistsItem $item
     */
    public function __construct(pocketlistsItem $item)
    {
        $this->item = $item;
    }

    /**
     * @return mixed|string
     * @throws waException
     */
    public function getName()
    {
        return pocketlistsNaturalInput::matchLinks($this->item->getName());
    }

    /**
     * @return mixed|string
     * @throws waException
     */
    public function getNote()
    {
        return pocketlistsNaturalInput::matchLinks($this->item->getNote());
    }
}
