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
     * @return array
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

            /** @var waContact $mate */
            $mate = new waContact($tid);

            if ($mate->get('is_user') == -1) {
                continue;
            }
//            if (!$mate) {
//                continue;
//            }

//            $teammate = new pocketlistsTeammate($mate);
//            $teammate->fillData(pocketlistsHelper::getContactData($mate));

            if ($exclude_deleted && !$mate->exists()) {
                continue;
            }

            $teammates[$tid] = pocketlistsHelper::getContactData($mate);

            $teammates[$tid]['last_activity'] = isset($last_activities[$tid]) ? $last_activities[$tid] : 0;
            $teammates[$tid]['items_info'] = [
                'count'        => 0,
                'names'        => "",
                'max_priority' => 0,
            ];

            if (isset($items_count_names[$tid])) {
                $teammates[$tid]['items_info'] = [
                    'count'        => count($items_count_names[$tid]['item_names']),
                    'names'        => implode(', ', $items_count_names[$tid]['item_names']),
                    'max_priority' => $items_count_names[$tid]['item_max_priority'],
                ];
            }
        }

        if ($sort_by_last_activity) {
            usort($teammates, [$this, "compare_last_activity"]);
        }

        // todo: cache
        return $teammates;
    }

    /**
     * @param $a
     * @param $b
     *
     * @return false|int
     */
    private function compare_last_activity($a, $b)
    {
        $delta = strtotime($b['last_activity']) - strtotime($a['last_activity']);
        if (!$delta) {
            $delta = $a['id'] - $b['id'];
        }

        return $delta;
    }
}
