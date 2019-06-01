<?php

/**
 * Class pocketlistsTeammateFactory
 */
class pocketlistsContactFactory extends pocketlistsFactory
{
    /**
     * @var pocketlistsContact[]
     */
    protected $contactsCache = [];

    /**
     * @param int $contactId
     *
     * @return pocketlistsContact
     * @throws waException
     */
    public function createNewWithId($contactId)
    {
        if (!isset($this->contactsCache[$contactId])) {
            $this->contactsCache[$contactId] = new pocketlistsContact(new waContact($contactId));
        }

        return $this->contactsCache[$contactId];
    }

    /**
     * @param array $contactIds
     *
     * @return pocketlistsContact[]
     * @throws waException
     */
    public function createNewWithIds($contactIds)
    {
        $contacts = [];

        foreach ($contactIds as $contactId) {
            $contacts[] = $this->createNewWithId($contactId);
        }

        return $contacts;
    }

    /**
     * @param array $teammates_ids
     * @param bool  $sort_by_last_activity
     * @param bool  $exclude_me
     * @param bool  $exclude_deleted
     *
     * @return pocketlistsContact[]
     * @throws waException
     */
    public function getTeammates(
        $teammates_ids,
        $sort_by_last_activity = true,
        $exclude_me = true,
        $exclude_deleted = false
    ) {
        /** @var pocketlistsContact[] $teammates */
        $teammates = $this->createNewWithIds($teammates_ids);

        $itemsCounts = pl2()->getEntityCounter()->getAssignedItemsCountAndNames($teammates);

        $last_activities = $sort_by_last_activity
            ? pl2()->getModel(pocketlistsItem::class)->getLastActivities($teammates_ids)
            : [];

        foreach ($teammates as $i => $teammate) {
            if ($exclude_me && $teammate->isMe()) {
                unset($teammates[$i]);

                continue;
            }

            if ($exclude_deleted && !$teammate->isExists()) {
                unset($teammates[$i]);

                continue;
            }

//            if ($teammate->isExists()) {
//                continue;
//            }

            $teammate->setLastActivity(
                isset($last_activities[$teammate->getId()]) ? $last_activities[$teammate->getId()] : 0
            );

            if (isset($itemsCounts[$teammate->getId()])) {
                $teammate->setItemsInfo($itemsCounts[$teammate->getId()]);
            }
        }

        if ($sort_by_last_activity) {
            usort($teammates, [$this, 'compare_last_activity']);
        }

        // todo: cache
        return $teammates;
    }

    /**
     * @param pocketlistsContact $a
     * @param pocketlistsContact $b
     *
     * @return false|int
     */
    private function compare_last_activity($a, $b)
    {
        $delta = strtotime($b->getLastActivity()) - strtotime($a->getLastActivity());
        if (!$delta) {
            $delta = $a->getId() - $b->getId();
        }

        return $delta;
    }
}
