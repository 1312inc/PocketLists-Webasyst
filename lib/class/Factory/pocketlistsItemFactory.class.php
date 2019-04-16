<?php

/**
 * Class pocketlistsFactoryItem
 *
 * @method pocketlistsItemModel getModel()
 */
class pocketlistsItemFactory extends pocketlistsFactory
{
    protected $entity = 'pocketlistsItem';

    /**
     * @param pocketlistsItemLink $itemLink
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findForLinkedEntity(pocketlistsItemLink $itemLink)
    {
        $data = $this->getModel()->getAppItems(
            $itemLink->getApp(),
            $itemLink->getEntityType(),
            $itemLink->getEntityId()
        );

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsAppLinkInterface $app
     * @param string                      $entityType
     * @param int                         $entityId
     * @param string                      $date
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findAllForApp(pocketlistsAppLinkInterface $app, $entityType = '', $entityId = 0, $date = '')
    {
        $data = $this->getModel()->getAppItems($app->getApp(), $entityType, $entityId, $date);

        return $this->generateWithData($data, true);
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
     * @param bool               $date
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
     * @param bool               $date
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findToDoRecap(pocketlistsContact $contact, $date = false)
    {
        $data = $this->getModel()->getDailyRecapItems($contact->getId(), $date);

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
     * @param array|int $contactIds
     *
     * @return array
     * @throws waException
     */
    public function findAssignedItemsCountAndNames($contactIds)
    {
        if (!is_array($contactIds)) {
            $contactIds = [$contactIds];
        }

        $result = [];

        foreach ($contactIds as $contact_id) {
            $result[$contact_id] = $this->getModel()->countAssignedOrCompletesByContactItems($contact_id);
        }

        return $result;
    }

    /**
     * @param pocketlistsContact|null $contact
     * @param bool                    $date_range
     * @param bool                    $completed
     * @param int                     $start
     * @param int                     $limit
     *
     * @return array|mixed
     * @throws waException
     */
    public function findLogbook(
        pocketlistsContact $contact = null,
        $date_range = false,
        $completed = false,
        $start = 0,
        $limit = 50
    ) {
        $data = $this->getModel()
            ->getLogbookItems($contact ? $contact->getId() : false, $date_range, $completed, $start, $limit);

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsItem $item
     *
     * @return bool
     * @throws waException
     */
    public function delete(pocketlistsEntity $item)
    {
        pl2()->getModel(pocketlistsComment::class)->deleteByField('item_id', $item->getId());
        pl2()->getModel('pocketlistsUserFavorites')->deleteByField('item_id', $item->getId());
        pl2()->getModel('pocketlistsItemTags')->deleteByField('item_id', $item->getId());
        pl2()->getModel('pocketlistsItemLink')->deleteByField('item_id', $item->getId());
        pl2()->getModel('pocketlistsItemSort')->deleteByField('item_id', $item->getId());

        /** @var pocketlistsAttachmentFactory $attachmentFactory */
        $attachmentFactory = pl2()->getEntityFactory(pocketlistsAttachment::class);
        $attachmentFactory->deleteAllByItem($item);

        return parent::delete($item);
    }

    /**
     * @param pocketlistsItem[] $items
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function updateProperSort(array $items)
    {
        $sort = 0;
        foreach ($items as $item) {
            $this->getModel()->updateById($item->getId(), ['sort' => $sort++]);
        }

        return $items;
    }

    /**
     * @param pocketlistsItem[] $items
     * @param pocketlistsList   $list
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
