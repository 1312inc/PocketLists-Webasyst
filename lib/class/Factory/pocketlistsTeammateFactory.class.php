<?php

/**
 * Class pocketlistsTeammateFactory
 */
class pocketlistsTeammateFactory extends pocketlistsFactory
{
    /**
     * @return kmModelExt
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param      $teammates_ids
     * @param bool $sort_by_last_activity
     * @param bool $exclude_me
     * @param bool $exclude_deleted
     *
     * @return pocketlistsContact[]
     * @throws waDbException
     * @throws waException
     */
    public function getTeammates(
        $teammates_ids,
        $sort_by_last_activity = true,
        $exclude_me = true,
        $exclude_deleted = false
    ) {
        $teammates = [];

        $im = new pocketlistsItemModel();
        $items_count_names = $im->getAssignedItemsCountAndNames($teammates_ids);
        $last_activities = $sort_by_last_activity ? $im->getLastActivities($teammates_ids) : [];
        foreach ($teammates_ids as $tid) {
            if ($exclude_me && $tid == wa()->getUser()->getId()) {
                continue;
            }

//            if (!$mate) {
//                continue;
//            }

//            $teammate = new pocketlistsTeammate($mate);
//            $teammate->fillData(pocketlistsHelper::getContactData($mate));

            $mate = new pocketlistsContact(new waContact($tid));

            if ($exclude_deleted && !$mate->isExists()) {
                continue;
            }

            if ($mate->isExists() && $mate->getContact()->get('is_user') == -1) {
                continue;
            }

            $mate->setLastActivity(isset($last_activities[$tid]) ? $last_activities[$tid] : 0);

            if (isset($items_count_names[$tid])) {
                $mate->getItemsInfo([
                    'count'        => count($items_count_names[$tid]['item_names']),
                    'names'        => implode(', ', $items_count_names[$tid]['item_names']),
                    'max_priority' => $items_count_names[$tid]['item_max_priority'],
                ]);
            }

            $teammates[$tid] = $mate;
        }

        if ($sort_by_last_activity) {
            usort($teammates, [$this, "compare_last_activity"]);
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
