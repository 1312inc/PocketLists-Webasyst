<?php

/**
 * Class pocketlistsRepetitions
 */
class pocketlistsRepetitions
{
    const WEEK = [
        1 => 'Sunday',	    //	Воскресенье
        2 => 'Monday',	    //	Понедельник
        3 => 'Tuesday',	    //	Вторник
        4 => 'Wednesday',	//	Среда
        5 => 'Thursday',	//	Четверг
        6 => 'Friday',	    //	Пятница
        7 => 'Saturday'	    //	Суббота
    ];

    /**
     * @return bool
     * @throws waDbException
     * @throws waException
     */
    static public function repeatLists()
    {
        $model = pl2()->getModel(pocketlistsItem::class);
        $list_by_months = $model->query('
            SELECT pl.*, pli.name, pli.due_date, pli.due_datetime, pli.repeat_frequency, pli.repeat_interval, pli.repeat_occurrence, DAYOFWEEK(pli.due_date) AS week_day
            FROM pocketlists_list pl
            LEFT JOIN pocketlists_item pli ON pli.id = pl.key_item_id
            WHERE pli.repeat_frequency > 0
            AND pli.repeat_interval IS NOT NULL
            AND pli.due_date <= s:due_date
            ORDER BY pli.repeat_interval
        ', ['due_date' => date('Y-m-d')])->fetchAll('id');

        $repeat_lists = [];
        foreach ($list_by_months as $_list) {
            if (!in_array($_list['repeat_interval'], pocketlistsItem::REPEAT_INTERVAL)) {
                continue;
            }

            switch ($_list['repeat_interval']) {
                case pocketlistsItem::INTERVAL_DAY:
                    $repeat_lists[] = $_list;
                    break;
                case pocketlistsItem::INTERVAL_WORKDAY:
                    if (in_array($_list['week_day'], [2, 3, 4, 5, 6])) {
                        $repeat_lists[] = $_list;
                    }
                    break;
                case pocketlistsItem::INTERVAL_WEEK:
                    if (in_array($_list['week_day'], [1, 7])) {
                        $repeat_lists[] = $_list;
                    }
                    break;
                case pocketlistsItem::INTERVAL_MONTH:
                    if ($_list['due_date'] >= date('Y-m-d', strtotime('-1 month'))) {
                        $repeat_lists[] = $_list;
                    }
                    break;
                case pocketlistsItem::INTERVAL_YEAR:
                    if ($_list['due_date'] >= date('Y-m-d', strtotime('-1 year'))) {
                        $repeat_lists[] = $_list;
                    }
                    break;
            }
        }

        if ($repeat_lists) {
            $key_items = $model->query('
                SELECT * FROM pocketlists_item 
                WHERE id IN (i:key_item_ids)
            ', ['key_item_ids' => array_column($repeat_lists, 'key_item_id')])->fetchAll('id');

            try {
                $clone_lists = self::cloneLists($repeat_lists, $key_items);
            } catch (Exception $e) {
                pocketlistsLogger::error($e->getMessage(), 'repeating.log');
                return false;
            }

            try {
                self::cloneItems($clone_lists);
            } catch (Exception $e) {
                pocketlistsLogger::error($e->getMessage(), 'repeating.log');
                return false;
            }

            $model->updateByField(
                ['key_list_id' => array_column($clone_lists, 'old_id')],
                ['repeat_frequency' => 0, 'repeat_interval'  => '']
            );
        }

        return true;
    }

    /**
     * @param $lists
     * @param $key_items
     * @return array|mixed
     * @throws waException
     */
    static private function cloneLists($lists = [], $key_items = [])
    {
        /** @var pocketlistsListFactory $list_factory */
        $list_factory = pl2()->getEntityFactory(pocketlistsList::class);

        foreach ($lists as $key => &$_list) {
            $_list += ifset($key_items, $_list['key_item_id'], []);
            $_list['old_id'] = $_list['id'];
            $_list['activity_datetime'] = date('Y-m-d H:i:s');
            $_list['repeat_occurrence'] += 1;
            $_list['name'] .= ' > '.$_list['repeat_occurrence'];
            $_list['uuid'] = waString::uuid();
            switch ($_list['repeat_interval']) {
                case pocketlistsItem::INTERVAL_DAY:
                    $_list['due_date'] = date('Y-m-d', strtotime($_list['due_date'].' next day'));
                    break;
                case pocketlistsItem::INTERVAL_WORKDAY:
                    break;
                case pocketlistsItem::INTERVAL_WEEK:
                    break;
                case pocketlistsItem::INTERVAL_MONTH:
                    $_list['due_date'] = date('Y-m-d', strtotime($_list['due_date'].' next month'));
                    break;
                case pocketlistsItem::INTERVAL_YEAR:
                    $_list['due_date'] = date('Y-m-d', strtotime($_list['due_date'].' next year'));
                    break;
            }
            unset($_list['id'], $_list['key_item_id'], $_list['complete_datetime']);

            $list_entity = $list_factory->generateWithData($_list);
            if ($list_factory->save($list_entity)) {
                $_list['id'] = $list_entity->getId();
            } else {
                pocketlistsLogger::error('Error clone list. Data list: '.var_export($_list, true), 'repeating.log');
                unset($_list[$key]);
            }
        }

        return $lists;
    }

    /**
     * @param $clone_lists
     * @return void
     * @throws waException
     */
    static private function cloneItems($clone_lists)
    {
        $model = pl2()->getModel(pocketlistsItem::class);
        foreach ($clone_lists as $list) {
            try {
                $result = $model->exec("
                    INSERT INTO pocketlists_item (list_id, contact_id, parent_id, sort, `rank`, has_children, priority, calc_priority, create_datetime, update_datetime, activity_datetime, client_touch_datetime, name, note, location_id, amount, currency_iso3, assigned_contact_id, repeat_frequency, repeat_interval, repeat_occurrence, key_list_id, uuid, pro_label_id)
                    SELECT i:new_list_id AS list_id, contact_id, parent_id, sort, `rank`, has_children, priority, calc_priority, create_datetime, update_datetime, activity_datetime, client_touch_datetime, name, note, location_id, amount, currency_iso3, assigned_contact_id, repeat_frequency, repeat_interval, repeat_occurrence, key_list_id, '' AS uuid, pro_label_id
                    FROM pocketlists_item pli
                    WHERE pli.list_id i:old_list_id
                ", ['new_list_id' => $list['id'], 'old_list_id' => $list['old_id']]);

                if (!$result) {
                    pocketlistsLogger::error('Error clone items. Data list: '.var_export($list, true), 'repeating.log');
                }
            } catch (Exception $e) {
                pocketlistsLogger::error('Error clone items. Data list: '.var_export($list, true).' Exception: '.$e->getMessage(), 'repeating.log');
            }
        }
    }
}
