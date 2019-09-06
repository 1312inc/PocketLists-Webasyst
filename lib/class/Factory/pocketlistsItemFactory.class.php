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
     * @param array                       $date
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findAllForApp(pocketlistsAppLinkInterface $app, $entityType = '', $entityId = 0, $date = [])
    {
        $data = $this->getModel()->getAppItems($app->getApp(), $entityType, $entityId, $date);

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsAppLinkInterface $app
     * @param string                      $entityType
     * @param int                         $entityId
     * @param array                       $date
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findDoneForApp(pocketlistsAppLinkInterface $app, $entityType = '', $entityId = 0, $date = [])
    {
        $data = $this->getModel()->getAppItems(
            $app->getApp(),
            $entityType,
            $entityId,
            $date,
            pocketlistsItem::STATUS_DONE,
            $this->getLimit(),
            $this->getOffset()
        );

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsAppLinkInterface $app
     * @param string                      $entityType
     * @param int                         $entityId
     * @param array                       $date
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findUndoneForApp(pocketlistsAppLinkInterface $app, $entityType = '', $entityId = 0, $date = [])
    {
        $data = $this->getModel()->getAppItems(
            $app->getApp(),
            $entityType,
            $entityId,
            $date,
            pocketlistsItem::STATUS_UNDONE,
            $this->getLimit(),
            $this->getOffset()
        );

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
    public function findDoneByList(pocketlistsList $list, $tree = true)
    {
        $data = $this->getModel()->getDoneByList($list->getId(), $this->getOffset(), $this->getLimit(), $tree);

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
     * @param                 $date
     * @param null            $status
     *
     * @return array|mixed
     * @throws waDbException
     * @throws waException
     */
    public function findFavoritesForUserAndDate(pocketlistsUser $user, $date, $status = null)
    {
        $data = $this->getModel()->getFavorites(
            $user->getContact()->getId(),
            $date,
            false,
            $status,
            $this->getOffset(),
            $this->getLimit()
        );

        $this->resetLimitAndOffset();

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsUser $user
     * @param                 $date
     *
     * @return array|mixed
     * @throws waDbException
     * @throws waException
     */
    public function findFavoritesUndoneForUserAndDate(pocketlistsUser $user, $date)
    {
        return $this->findFavoritesForUserAndDate($user, $date, pocketlistsItem::STATUS_UNDONE);
    }

    /**
     * @param pocketlistsUser $user
     * @param                 $date
     *
     * @return array|mixed
     * @throws waDbException
     * @throws waException
     */
    public function findFavoritesDoneForUserAndDate(pocketlistsUser $user, $date)
    {
        return $this->findFavoritesForUserAndDate($user, $date, pocketlistsItem::STATUS_DONE);
    }

    /**
     * @param pocketlistsContact $contact
     * @param array              $dateBounds
     * @param null               $status
     *
     * @return array|mixed
     * @throws waDbException
     * @throws waException
     */
    public function findToDo(pocketlistsContact $contact, $dateBounds = [], $status = null)
    {
        $dateBounds = $dateBounds ?: [];

        if (!is_array($dateBounds)) {
            $dateBounds = [$dateBounds];
        }

        $data = $this->getModel()->getToDo(
            $contact->getId(),
            $dateBounds,
            $status,
            $this->getOffset(),
            $this->getLimit()
        );

        $this->resetLimitAndOffset();

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsContact $contact
     * @param array              $dateBounds
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findToDoDone(pocketlistsContact $contact, $dateBounds = [])
    {
        return $this->findToDo($contact, $dateBounds, pocketlistsItem::STATUS_DONE);
    }

    /**
     * @param pocketlistsContact $contact
     * @param array              $dateBounds
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findToDoUndone(pocketlistsContact $contact, $dateBounds = [])
    {
        return $this->findToDo($contact, $dateBounds, pocketlistsItem::STATUS_UNDONE);
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
     * @param null               $status
     *
     * @return array|mixed
     * @throws waDbException
     * @throws waException
     */
    public function findAssignedOrCompletesByContact(pocketlistsContact $contact, $status = null)
    {
        $data = $this->getModel()->getAssignedOrCompletesByContactItems(
            $contact->getId(),
            $status,
            $this->getLimit(),
            $this->getOffset()
        );

        $this->resetLimitAndOffset();

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsContact $contact
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findAssignedOrCompletesUndoneByContact(pocketlistsContact $contact)
    {
        return $this->findAssignedOrCompletesByContact($contact, pocketlistsItem::STATUS_UNDONE);
    }

    /**
     * @param pocketlistsContact $contact
     *
     * @return pocketlistsItem[]
     * @throws waException
     */
    public function findAssignedOrCompletesDoneByContact(pocketlistsContact $contact)
    {
        return $this->findAssignedOrCompletesByContact($contact, pocketlistsItem::STATUS_DONE);
    }

    /**
     * @param pocketlistsContact|null $contact
     * @param bool                    $date_range
     * @param bool                    $completed
     *
     * @return array|mixed
     * @throws waException
     */
    public function findLogbook(pocketlistsContact $contact = null, $date_range = false, $completed = false)
    {
        $data = $this->getModel()
            ->getLogbookItems(
                $contact ? $contact->getId() : false,
                $date_range,
                $completed,
                0,
                $this->getOffset(),
                $this->getLimit()
            );

        $this->resetLimitAndOffset();

        return $this->generateWithData($data, true);
    }

    /**
     * @param pocketlistsPocket       $pocket
     * @param pocketlistsContact|null $contact
     * @param bool                    $date_range
     * @param bool                    $completed
     *
     * @return array|mixed
     * @throws waDbException
     * @throws waException
     */
    public function findLogbookForPocket(pocketlistsPocket $pocket, pocketlistsContact $contact = null, $date_range = false, $completed = false)
    {
        $data = $this->getModel()
            ->getLogbookItems(
                $contact ? $contact->getId() : false,
                $date_range,
                $completed,
                $pocket->getId(),
                $this->getOffset(),
                $this->getLimit()
            );

        $this->resetLimitAndOffset();

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

    public function searchByTerm($term)
    {

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
