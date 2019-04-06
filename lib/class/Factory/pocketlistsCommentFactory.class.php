<?php

/**
 * Class pocketlistsPocketFactory
 *
 * @method pocketlistsCommentModel getModel()
 */
class pocketlistsCommentFactory extends pocketlistsFactory
{
    protected  $entity;

    /**
     * @param pocketlistsItem $item
     *
     * @return pocketlistsComment[]
     * @throws waException
     */
    public function findForItem(pocketlistsItem $item)
    {
        $data = $this->getModel()->getAllByItems($item->getId());

        if (isset($data[$item->getId()])) {
            $data = $data[$item->getId()];
        } else {
            return [];
        }

        $comments = $this->generateWithData($data, true);

        /** @var pocketlistsComment $comment */
        foreach ($comments as $comment) {
            $comment->setItem($item);
        }

        return $comments;
    }

    /**
     * @param int $start
     * @param int $limit
     *
     * @return pocketlistsComment[]
     * @throws waDbException
     * @throws waException
     */
    public function findForPage($start, $limit)
    {
        $data = $this->getModel()->getComments($start, $limit);

        return $this->generateWithData($data, true);
    }
}
