<?php

/**
 * Class pocketlistsPocketFactory
 *
 * @method pocketlistsCommentModel getModel()
 */
class pocketlistsCommentFactory extends pocketlistsFactory
{
    protected $entity = 'pocketlistsComment';

    /**
     * @param pocketlistsItem $item
     *
     * @return pocketlistsComment[]
     * @throws waException
     */
    public function findForItem(pocketlistsItem $item)
    {
        $key = "comment_for_item_{$item->getId()}";
        $comments = $this->getFromCache($key);
        if ($comments) {
            return $comments;
        }

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

        $this->cache($key, $comments);

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
