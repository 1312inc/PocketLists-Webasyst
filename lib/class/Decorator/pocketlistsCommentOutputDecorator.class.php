<?php

/**
 * Class pocketlistsCommentOutputDecorator
 *
 * @method pocketlistsComment __construct(pocketlistsEntity $object)
 * @method pocketlistsComment getObject()
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
