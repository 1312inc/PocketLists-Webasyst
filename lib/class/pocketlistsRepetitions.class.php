<?php

/**
 * Class pocketlistsRepetitions
 */
class pocketlistsRepetitions
{
    use pocketlistsDataHelperTrait;

    const WEEK = [
        1 => 'Sunday',	    // Воскресенье
        2 => 'Monday',	    // Понедельник
        3 => 'Tuesday',	    // Вторник
        4 => 'Wednesday',	// Среда
        5 => 'Thursday',	// Четверг
        6 => 'Friday',	    // Пятница
        7 => 'Saturday'	    // Суббота
    ];

    private static $instance;

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

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
                ['repeat_frequency' => 0, 'repeat_interval'  => '', 'activity_datetime' => date('Y-m-d H:i:s')]
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
            $_list['archived'] = 0;
            $_list['key_item_id'] = null;
            $_list['complete_datetime'] = null;
            $_list['repeat_occurrence'] += 1;
            $_list['uuid'] = waString::uuid();
            switch ($_list['repeat_interval']) {
                case pocketlistsItem::INTERVAL_DAY:
                    $_list['due_date'] = date('Y-m-d', strtotime($_list['due_date'].' next day'));
                    break;
                case pocketlistsItem::INTERVAL_WORKDAY:
                case pocketlistsItem::INTERVAL_WEEK:
                    break;
                case pocketlistsItem::INTERVAL_MONTH:
                    $_list['due_date'] = date('Y-m-d', strtotime($_list['due_date'].' next month'));
                    break;
                case pocketlistsItem::INTERVAL_YEAR:
                    $_list['due_date'] = date('Y-m-d', strtotime($_list['due_date'].' next year'));
                    break;
            }
            unset($_list['id']);

            $list_entity = $list_factory->generateWithData($_list);
            if ($list_factory->save($list_entity)) {
                $_list['id'] = $list_entity->getId();
            } else {
                pocketlistsLogger::error('Error clone list. Data list: '.var_export($_list, true), 'repeating.log');
                unset($lists[$key]);
            }
        }

        if (!empty($lists)) {
            $right_names = array_map(function ($l_id) {return "list.$l_id";}, array_column($lists, 'old_id'));
            $cr_model = new waContactRightsModel();
            $right_lists = $cr_model->select('*')->where('app_id = ? AND name IN (?)', [pocketlistsHelper::APP_ID, $right_names])->fetchAll();

            foreach ($right_lists as &$r_list) {
                $old_right_list_id = explode('.', $r_list['name'])[1];
                foreach ($lists as $l) {
                    if ($old_right_list_id == $l['old_id']) {
                        $r_list['name'] = 'list.'.$l['id'];
                        break;
                    }
                }
            }
            unset($r_list);

            if (!empty($right_lists)) {
                $cr_model->multipleInsert($right_lists);
            }
        }

        self::getInstance()->saveLog(
            pocketlistsLog::ENTITY_LIST,
            pocketlistsLog::ACTION_ADD,
            $lists
        );

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
                $items = $model->select('*')->where('list_id = ?', $list['old_id'])->fetchAll();
                foreach ($items as &$_item) {
                    $_item['old_id'] = $_item['id'];
                    $_item['list_id'] = $list['id'];
                    $_item['uuid'] = waString::uuid();
                    $_item['status'] = pocketlistsItem::STATUS_UNDONE;
                    $_item['create_datetime'] = date('Y-m-d H:i:s');
                    $_item['activity_datetime'] = null;
                    $_item['complete_datetime'] = null;
                    $_item['complete_contact_id'] = null;
                    unset($_item['id']);
                }

                $item_model = pl2()->getModel(pocketlistsItem::class);
                $result = $item_model->multipleInsert($items);
                if ($result->getResult()) {
                    $last_id = $result->lastInsertId();
                    $rows_count = $result->affectedRows();
                    if ($rows_count === count($items)) {
                        foreach ($items as &$_item) {
                            $_item['id'] = $last_id++;
                            self::cloneAttachments($_item['old_id'], $_item['id']);
                        }
                        self::getInstance()->saveLog(
                            pocketlistsLog::ENTITY_ITEM,
                            pocketlistsLog::ACTION_ADD,
                            $items
                        );
                    }
                }
            } catch (Exception $e) {
                pocketlistsLogger::error('Error clone items. Data list: '.var_export($list, true).' Exception: '.$e->getMessage(), 'repeating.log');
            }
        }
    }

    /**
     * @param $old_item_id
     * @param $new_item_id
     * @return void
     * @throws waException
     */
    static private function cloneAttachments($old_item_id, $new_item_id)
    {
        $wa_data_path = wa()->getDataPath(pocketlistsUploadedFileVO::PATH, false, pocketlistsHelper::APP_ID);
        $model = pl2()->getModel(pocketlistsAttachment::class);
        $attachments = $model->select('*')->where('item_id = ?', $old_item_id)->fetchAll();

        if (empty($attachments)) {
            return;
        }
        try {
            waFiles::copy($wa_data_path.DIRECTORY_SEPARATOR.$old_item_id, $wa_data_path.DIRECTORY_SEPARATOR.$new_item_id);

            foreach ($attachments as &$_attachment) {
                $_attachment['item_id'] = $new_item_id;
                $_attachment['uuid'] = waString::uuid();
                unset($_attachment['id']);
            }
            $result = $model->multipleInsert($attachments);
            if ($result->getResult()) {
                $last_id = $result->lastInsertId();
                $rows_count = $result->affectedRows();
                if ($rows_count === count($attachments)) {
                    foreach ($attachments as &$_attachment) {
                        $_attachment['id'] = $last_id++;
                    }
                }
            }

            self::getInstance()->saveLog(
                pocketlistsLog::ENTITY_ATTACHMENT,
                pocketlistsLog::ACTION_ADD,
                $attachments
            );
        } catch (Exception $e) {
            pocketlistsLogger::error('Error clone attachment directory. Data attachments: '.var_export($attachments, true).' Exception: '.$e->getMessage(), 'repeating.log');
        }
    }
}
