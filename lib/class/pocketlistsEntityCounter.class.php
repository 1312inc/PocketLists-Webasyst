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

        return $this->cache($key, new pocketlistsItemsCount($count));
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

        return $this->cache($key, new pocketlistsItemsCount($count));
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

        return $this->cache($key, new pocketlistsItemsCount($count));
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
     * @param pocketlistsList $list
     *
     * @return array|pocketlistsItemsCount|null
     * @throws waException
     */
    public function getListItemsCount(pocketlistsList $list)
    {
        $key = sprintf('items|list|%s', $list->getId());

        // todo: оставить кеш, но инвалидировать в нужный момент
//        $count = $this->getFromCache($key);
//        if ($count instanceof pocketlistsItemsCount) {
//            return $count;
//        }

        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);

        $count = $itemModel->countListItems([$list->getId()], pocketlistsItem::STATUS_UNDONE);

        if (isset($count[$list->getId()])) {
            $count = array_combine(
                array_column($count[$list->getId()], 'priority'),
                array_column($count[$list->getId()], 'count')
            );
            $count = new pocketlistsItemsCount($count);
        } else {
            $count = new pocketlistsItemsCount();
        }

        return $this->cache($key, $count);
    }

    /**
     * @param pocketlistsAppLinkInterface $app
     * @param string                      $entityType
     * @param array                       $entityIds
     *
     * @return pocketlistsItemsCount[]
     * @throws waException
     */
    public function countLinkedItemsByAppAndEntities(pocketlistsAppLinkInterface $app, $entityType, array $entityIds)
    {
        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);

        $count = $itemModel->countLinkedItemsByAppAndEntities(
            $app->getApp(),
            $entityType,
            $entityIds
        );

        $counts = [];
        foreach ($count as $entityId => $item) {
            $itemsCount = array_combine(
                array_column($item, 'calc_priority'),
                array_column($item, 'count')
            );

            $counts[$entityId] = new pocketlistsItemsCount($itemsCount);
        }

        return $counts;
    }

    /**
     * @param pocketlistsAppLinkInterface $app
     * @param string                      $entityType
     * @param int                       $entityId
     *
     * @return pocketlistsItemsCount
     * @throws waException
     */
    public function countLinkedItemByAppAndEntities(pocketlistsAppLinkInterface $app, $entityType, $entityId)
    {
        /** @var pocketlistsItemModel $itemModel */
        $itemModel = pl2()->getModel(pocketlistsItem::class);

        $count = $itemModel->countLinkedItemsByAppAndEntities(
            $app->getApp(),
            $entityType,
            [$entityId]
        );

        $itemsCount = [];
        if ($count) {
            $entityId = key($count);
            $item = $count[$entityId];

            $itemsCount = array_combine(
                array_column($item, 'calc_priority'),
                array_column($item, 'count')
            );
        }

        return new pocketlistsItemsCount($itemsCount);
    }

    /**
     * @param $key
     *
     * @return pocketlistsItemsCount|null
     */
    private function getFromCache($key)
    {
//        if (isset($this->cache[$key])) {
//            return $this->cache[$key];
//        }
//
        return pl2()->getCache()->get($key);
    }

    /**
     * @param string                $key
     * @param pocketlistsItemsCount $count
     * @param int                   $ttl
     *
     * @return pocketlistsItemsCount
     */
    private function cache($key, pocketlistsItemsCount $count, $ttl = 60)
    {
//        $this->cache[$key] = $count;

        pl2()->getCache()->set($key, $count, $ttl);

        return $count;
    }
}
