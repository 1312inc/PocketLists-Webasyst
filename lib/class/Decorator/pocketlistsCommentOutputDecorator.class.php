<?php

/**
 * Class pocketlistsCommentOutputDecorator
 */
class pocketlistsCommentOutputDecorator extends pocketlistsDecorator
{
    /**
     * @var pocketlistsComment
     */
    protected $object;

    /**
     * @return mixed|string
     * @throws waException
     */
    public function getComment()
    {
        return pocketlistsNaturalInput::matchLinks($this->object->getComment());
    }
}
