<?php

/**
 * Class pocketlistsEntityCounter
 */
class pocketlistsEntityCounter
{
    /**
     * @var pocketlistsItemsCount[]
     */
    private $cache = [];

    /**
     * @param pocketlistsUser $user
     * @param array           $priorities
     * @param null            $status
     * @param array           $date
     *
     * @return pocketlistsItemsCount
     * @throws waDbException
     * @throws waException
     */
    public function countTodoItems(pocketlistsUser $user, array $priorities = [], $status = null, $date = [])
    {
        $key = sprintf('items|todo|%s|%s|%s', $user->getId(), json_encode($priorities), $status);

        $count = $this->getFromCache($key);
        if ($count instanceof pocketlistsItemsCount) {
            return $count;
        }

        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);

        $count = $itemModel->countTodo($user->getId(), $date, $priorities, $status);
        list($count, $countPriority, $maxPriority) = $count ?: [0, 0, 0];

        return $this->cache($key, new pocketlistsItemsCount($count, $countPriority, $maxPriority));
    }

    /**
     * @param array                $priorities
     * @param pocketlistsUser|null $user
     *
     * @return pocketlistsItemsCount
     * @throws waDbException
     * @throws waException
     */
    public function countTodoDoneItems(array $priorities = [], pocketlistsUser $user = null)
    {
        $user = $user ?: pl2()->getUser();

        return $this->countTodoItems($user, $priorities, pocketlistsItem::STATUS_DONE);
    }

    /**
     * @param array                $priorities
     * @param pocketlistsUser|null $user
     *
     * @return pocketlistsItemsCount
     * @throws waDbException
     * @throws waException
     */
    public function countTodoUndoneItems(array $priorities = [], pocketlistsUser $user = null)
    {
        $user = $user ?: pl2()->getUser();

        return $this->countTodoItems($user, $priorities, pocketlistsItem::STATUS_UNDONE);
    }

    /**
     * @param pocketlistsUser|null $user
     *
     * @return pocketlistsItemsCount
     * @throws waDbException
     * @throws waException
     */
    public function countTodoUndoneWithUserPrioritiesItems(pocketlistsUser $user = null)
    {
        $user = $user ?: pl2()->getUser();

        $icon = $user->getSettings()->appIcon();

        $priorities = $user->getSettings()->getIconPrioririesMapping($icon);

        return $this->countTodoUndoneItems($priorities, $user);
    }

    /**
     * @param pocketlistsUser|null $user
     * @param bool                 $date
     * @param bool                 $date2
     * @param null                 $status
     *
     * @return pocketlistsItemsCount
     * @throws waDbException
     * @throws waException
     */
    public function countFavoritesItems(pocketlistsUser $user = null, $date = false, $date2 = false, $status = null)
    {
        $user = $user ?: pl2()->getUser();

        $key = sprintf('items|favorites|%s|%s|%s|%s', $user->getId(), $date, $date2, $status);

        $count = $this->getFromCache($key);
        if ($count instanceof pocketlistsItemsCount) {
            return $count;
        }

        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);

        $count = $itemModel->getFavoritesCount($user->getId(), $date, $date2, $status);
        list($count, $countPriority, $maxPriority) = $count ?: [0, 0, 0];

        return $this->cache($key, new pocketlistsItemsCount($count, $countPriority, $maxPriority));
    }

    /**
     * @param pocketlistsAppLinkInterface $app
     *
     * @return pocketlistsItemsCount
     */
    public function countAppItems(pocketlistsAppLinkInterface $app)
    {
        $key = sprintf('items|app|%s', $app->getApp());

        $count = $this->getFromCache($key);
        if ($count instanceof pocketlistsItemsCount) {
            return $count;
        }

        return $this->cache($key, $app->countItems());
    }

    /**
     * @param pocketlistsContact $user
     *
     * @return pocketlistsItemsCount|null
     * @throws waDbException
     * @throws waException
     */
    public function countTeammateItems(pocketlistsContact $user)
    {
        $key = sprintf('items|team|%s', $user->getId());

        $count = $this->getFromCache($key);
        if ($count instanceof pocketlistsItemsCount) {
            return $count;
        }

        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);

        $count = $itemModel->countAssignedOrCompletesByContactItems($user->getId());
        list($count, $countPriority, $maxPriority) = $count ?: [0, 0, 0];

        return $this->cache($key, new pocketlistsItemsCount($count, $countPriority, $maxPriority));
    }


    /**
     * @param pocketlistsContact|pocketlistsContact[] $teammates
     *
     * @return pocketlistsItemsCount[]
     * @throws waException
     */
    public function getAssignedItemsCountAndNames($teammates)
    {
        if ($teammates instanceof pocketlistsContact) {
            $teammates = [$teammates];
        }

        $result = [];

        foreach ($teammates as $teammate) {
            $result[$teammate->getId()] = $this->countTeammateItems($teammate);
        }

        return $result;
    }

    /**
     * @param $key
     *
     * @return pocketlistsItemsCount|null
     */
    private function getFromCache($key)
    {
        if (isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        return null;
    }

    /**
     * @param string                $key
     * @param pocketlistsItemsCount $count
     * @param int                   $ttl
     *
     * @return pocketlistsItemsCount
     */
    private function cache($key, pocketlistsItemsCount $count, $ttl = 0)
    {
        $this->cache[$key] = $count;

        return $count;
    }
}
