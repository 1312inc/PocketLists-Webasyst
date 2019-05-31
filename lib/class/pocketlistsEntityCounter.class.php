<?php

/**
 * Class pocketlistsEntityCounter
 */
class pocketlistsEntityCounter
{
    /**
     * @var array
     */
    private $todosItemsCache = [];

    /**
     * @param pocketlistsUser $user
     * @param array           $priorities
     * @param null            $status
     *
     * @return int
     * @throws waDbException
     * @throws waException
     */
    public function countItemsTodo(pocketlistsUser $user, array $priorities = [], $status = null)
    {
        $key = sprintf('%s|%s|%s', $user->getId(), json_encode($priorities), $status);

        if (isset($this->todosItemsCache[$key])) {
            return $this->todosItemsCache[$key];
        }

        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);

        $count = (int)$itemModel->countTodo($user->getId(), [], $priorities, $status);

        $this->todosItemsCache[$key] = $count;

        return $this->todosItemsCache[$key];
    }

    /**
     * @param array                $priorities
     * @param pocketlistsUser|null $user
     *
     * @return int
     * @throws waDbException
     * @throws waException
     */
    public function countItemsTodoDone(array $priorities = [], pocketlistsUser $user = null)
    {
        $user = $user ?: pl2()->getUser();

        return $this->countItemsTodo($user, $priorities, pocketlistsItem::STATUS_DONE);
    }

    /**
     * @param array                $priorities
     * @param pocketlistsUser|null $user
     *
     * @return int
     * @throws waDbException
     * @throws waException
     */
    public function countItemsTodoUndone(array $priorities = [], pocketlistsUser $user = null)
    {
        $user = $user ?: pl2()->getUser();

        return $this->countItemsTodo($user, $priorities, pocketlistsItem::STATUS_UNDONE);
    }

    /**
     * @param pocketlistsUser|null $user
     *
     * @return int
     * @throws waDbException
     * @throws waException
     */
    public function countItemsTodoUndoneWithUserPriorities(pocketlistsUser $user = null)
    {
        $user = $user ?: pl2()->getUser();

        $icon = $user->getSettings()->appIcon();

        $priorities = $user->getSettings()->getIconPrioririesMapping($icon);

        return $this->countItemsTodoUndone($priorities, $user);
    }
}
