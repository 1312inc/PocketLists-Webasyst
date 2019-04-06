<?php

/**
 * Class pocketlistsFactoryItem
 *
 * @method pocketlistsItemModel getModel()
 */
class pocketlistsItemFactory extends pocketlistsFactory
{
    /**
     * @var
     */
    protected $entity;

    /**
     * @param pocketlistsItemLinkModel $itemLinkModel
     *
     * @return array
     */
    public function findForLinkedEntity(pocketlistsItemLinkModel $itemLinkModel)
    {
        return wa()->getConfig()
            ->getModel(pocketlistsItem::class)
            ->getAppItems($itemLinkModel->app, $itemLinkModel->entity_type, $itemLinkModel->entity_id);
    }

    /**
     * @param pocketlistsList $list
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findUndoneByList(pocketlistsList $list)
    {
        $data = $this->getModel()->getUndoneByList($list->getId());

        return $this->generateWithList($data, $list);
    }

    /**
     * @param pocketlistsList $list
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findByList(pocketlistsList $list)
    {
        $data = $this->getModel()->getAllByList($list->getId());

        return $this->generateWithList($data, $list);
    }

    /**
     * @param pocketlistsList $list
     * @param int             $offset
     * @param int             $limit
     * @param bool            $tree
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findDoneByList(pocketlistsList $list, $offset = 0, $limit = 10, $tree = true)
    {
        $data = $this->getModel()->getDoneByList($list->getId(), $offset, $limit, $tree);

        return $this->generateWithList($data, $list);
    }

    /**
     * @param pocketlistsUser $user
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findFavoritesForUser(pocketlistsUser $user)
    {
        $data = $this->getModel()->getFavorites($user->getContact()->getId(), false);

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsUser $user
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findFavoritesForUserAndDate(pocketlistsUser $user, $date)
    {
        $data = $this->getModel()->getFavorites($user->getContact()->getId(), $date);

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsContact $contact
     * @param bool            $date
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findToDo(pocketlistsContact $contact, $date = false)
    {
        $data = $this->getModel()->getToDo($contact->getId(), $date);

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsContact $contact
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findAssignedOrCompletesByContact(pocketlistsContact $contact)
    {
        $data = $this->getModel()->getAssignedOrCompletesByContactItems($contact->getId());

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsItem[] $items
     * @param pocketlistsList    $list
     *
     * @return pocketlistsItem[]
     */
    private function generateWithList($items, pocketlistsList $list)
    {
        if (!$items) {
            return [];
        }

        /** @var pocketlistsItem[] $items */
        $items = $this->generateWithData($items, true);
        foreach ($items as $item) {
            $item->setList($list);
        }

        return $items;
    }
}
