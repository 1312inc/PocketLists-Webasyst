<?php

/**
 * Class pocketlistsCommentOutputDecorator
 */
class pocketlistsCommentOutputDecorator
{
    /**
     * @var pocketlistsComment
     */
    private $comment;

    /**
     * pocketlistsCommentOutputDecorator constructor.
     *
     * @param pocketlistsComment $comment
     */
    public function __construct(pocketlistsComment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed|string
     * @throws waException
     */
    public function getComment()
    {
        return pocketlistsNaturalInput::matchLinks($this->comment->getComment());
    }
}
