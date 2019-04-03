<?php

class pocketlistsHelper
{
    const APP_ID = 'pocketlists';
    const COLOR_DEFAULT = 'blue';

    public static function getDueDatetime(&$date)
    {
        if (!empty($date['due_date']) &&
            !empty($date['due_datetime_hours']) &&
            !empty($date['due_datetime_minutes'])
        ) {
            $tm = strtotime($date['due_date'] . " " . $date['due_datetime_hours'] . ":" . $date['due_datetime_minutes'] . ":00");
            $tm = self::convertToServerTime($tm);
            $date['due_datetime'] = waDateTime::date("Y-m-d H:i:s", $tm);
            unset($date['due_datetime_hours']);
            unset($date['due_datetime_minutes']);
        } else {
            $date['due_datetime'] = null;
        }

        if (!empty($date['due_date'])) {
            $date['due_date'] = date("Y-m-d", strtotime($date['due_date']));
//            waDateTime::parse('date', waDateTime::format('date', $date['due_date']));
        } else {
            $date['due_date'] = null;
        }
    }

    // thanks to hub
    public static function convertToServerTime($timestamp)
    {
        $timezone = wa()->getUser()->getTimezone();
        $default_timezone = waDateTime::getDefaultTimeZone();
        if ($timezone && $timezone != $default_timezone) {
            $date_time = new DateTime(date('Y-m-d H:i:s', $timestamp), new DateTimeZone($timezone));
            $date_time->setTimezone(new DateTimeZone($default_timezone));
            $timestamp = (int)$date_time->format('U');
        }
        return $timestamp;
    }

    public static function calcPriorityOnDueDate($due_date, $due_datetime)
    {
        $now = time();
        $due_status = 0;

        if (!empty($due_date) || !empty($due_datetime)) {
            if (!empty($due_datetime) && $now > strtotime($due_datetime)) { // overdue datetime
                $due_status = pocketlistsItemModel::PRIORITY_RED;
            } elseif (strtotime(date("Y-m-d")) > strtotime($due_date)) { // overdue date
                $due_status = pocketlistsItemModel::PRIORITY_RED;
            } elseif ($due_date == date("Y-m-d")) { // today
                $due_status = pocketlistsItemModel::PRIORITY_YELLOW;
            } elseif ($due_date == date("Y-m-d", $now + 60 * 60 * 24)) { // tomorrow
                $due_status = pocketlistsItemModel::PRIORITY_GREEN;
            }
        }

        return $due_status;
    }

    public static function getItemChildIds($item_id, $item, &$return)
    {
        $return[] = $item['id'];
        foreach ($item['childs'] as $i) {
            self::getItemChildIds($item_id, $i, $return);
        }
    }

    public static function getMonthData(pocketlistsStrategyItemFilterAndSort $items, $show_month, $month_count = 1)
    {
        $timezone = wa()->getUser()->getTimezone();
        $month_date = waDateTime::date('Y-m', null, $timezone);
        if ($show_month) {
            $month_date = date('Y-m', strtotime(date('Y-m-01').' '.($show_month.' month')));
        }
        $month_date = strtotime($month_date);

        $list_colors = [];

        // completed items
        /** @var pocketlistsItem $item */
        foreach ($items->getItemsDone() as $item) {
            $list_colors[date('Y-m-d', strtotime($item->getCompleteDatetime()))]['gray'][] = $item['id'];
        }

        /** @var pocketlistsItem $item */
        foreach ($items->getItemsUndone() as $item) {
            if ($item->getDueDatetime() || $item->getDueDate()) {
                $due_date = date(
                    'Y-m-d',
                    strtotime($item->getDueDate() ? $item->getDueDate() : $item->getDueDatetime())
                );
                $list_color = $item->getListId() ? $item->getList()->getColor() : 'gray';
                $list_colors[$due_date]['color'][$list_color][] = $item->getId();
            }
        }

        $days = [];
        for ($i = 0; $i < $month_count; $i++) { // 3 month
            $days_count = date('t', $month_date);
            $first_day = date('w', $month_date);
            $last_day = date('w', strtotime(date('Y-m-{$days_count}', $month_date)));

            // first day is 'Sunday'
            if (waLocale::getFirstDay() === 7) {
                $first_day += 1;
                $last_day += 1;
            }
            $first_day = ($first_day === 0) ? 6 : $first_day - 1;
            $last_day = ($last_day === 0) ? 0 : 7 - $last_day;
            $date_start = strtotime('-'.$first_day.' days', $month_date);
            $date_end = strtotime('+'.($days_count + $last_day).' days', $month_date);

            $current_date_start = $date_start;
            $year = date('Y', $month_date);
            $month_name = date('F', $month_date);
            $month_num = (int)date('n', $month_date);
            $days[$year][$month_name] = [
                'weeks' => [],
                'num'   => $month_num,
            ];

            do {
                $week = (int)date('W', $current_date_start);
                $day = (int)date('w', $current_date_start);

                if (waLocale::getFirstDay() == 7 && $day == 0) {
                    $week = (int)date('W', strtotime('+1 week', $current_date_start));
                }

                if (!isset($days[$year][$month_name]['weeks'][$week])) {
                    $days[$year][$month_name]['days'][$week] = [];
                }
                $date_date = date('Y-m-d', $current_date_start);
                $hide_other_month_date = false;
                $current_month = date('n', $current_date_start);
                if ($month_num != $current_month) { // hide other month days
                    $hide_other_month_date = true;
                }
                if ($i == 0 && $current_date_start < $month_date) { // but show dates before first month
                    $hide_other_month_date = false;
                }
                if ($i == ($month_count - 1) && $current_date_start > $month_date) { // and after last month
                    $hide_other_month_date = false;
                }
                $days[$year][$month_name]['weeks'][$week][$day] = [
                    'date'  => [
                        'day'   => date('j', $current_date_start),
                        'month' => $current_month,
                        'date'  => $date_date,
                    ],
                    'hide'  => $hide_other_month_date,
                    'lists' => [
                        'color' => isset($list_colors[$date_date]['color']) ?
                            $list_colors[$date_date]['color'] : [],
                        'gray'  => isset($list_colors[$date_date]['gray']) ?
                            $list_colors[$date_date]['gray'] : [],
                    ],
//                        isset($list_colors[$date_date]) ? array_keys($list_colors[$date_date]) : array()
                ];
                $current_date_start = strtotime('+1 days', $current_date_start);
            } while ($date_end > $current_date_start);

            $month_date = strtotime('+1 month', $month_date);
        }

        return [
            'days'       => $days,
            'month_date' => $month_date,
        ];
    }

    /**
     * @param $contact waContact
     */
    public static function getContactData($contact)
    {
        $default = array(
            'name'      => '(DELETED USER)',
            'username'  => '(DELETED USER)',
            'id'        => 0,
            'photo_url' => '/wa-content/img/userpic96@2x.jpg',
            'userpic'   => '/wa-content/img/userpic20@2x.jpg',
            'status'    => false,
            'teamrole'  => '',
            'login'     => 'deleted',
            'me'        => false,
        );

        if (!$contact->exists()) {
            return $default;
        }

        return array(
            'name'      => $contact->getName(),
            'username'  => $contact->getName(),
            'id'        => $contact->getId(),
            'photo_url' => $contact->getPhoto(),
            'login'     => $contact->get('login'),
            'userpic'   => $contact->getPhoto(20),
            'status'    => $contact->getStatus(),
            'teamrole'  => $contact->get('jobtitle'),
            'me'        => ($contact->getId() == wa()->getUser()->getId()),
        );
    }

    /**
     * @param $fullseconds
     *
     * @return string
     */
    public static function getDatetimeBySeconds($fullseconds)
    {
        $hour = 60 * 60;
        $day = $hour * 24;
        $week = $day * 7;
        $month = $day * 31;
        $year = $month * 12;

        if ($fullseconds < 60) {
            return sprintf(_w('%d s'), $fullseconds);
        }

        if ($fullseconds < 60 * 60) {
            return sprintf(_w('%d min'), round(($fullseconds) / 60));
        }

        $minutes = round(($fullseconds / 60) % 60);
        $hours = round(($fullseconds / $hour) % 24);
        $days = round(($fullseconds / $day) % 31);
        $months = round(($fullseconds / $month) % 12);
        $years = round(($fullseconds / $year));

        if ($fullseconds < $day) {
            return sprintf(_w('%d h'), $hours, $minutes);
        }

        if ($fullseconds < $week) {
            return sprintf(_w('%d d'), $days, $hours);
        }

        if ($fullseconds < $month) {
            return sprintf(_w('%d d'), $days);
        }

        if ($fullseconds < $day * 365) {
            return sprintf(_w('%d mo'), $months, $days);
        }

        $yearDays = round(($fullseconds / $day) % 365);

        return sprintf(_w('%d y'), $years, $yearDays);
    }

    /**
     * @param string    $msg
     * @param Throwable $ex
     */
    public static function logError($msg, $ex)
    {
        waLog::log(
            sprintf("%s.\nException info: %s\n%s", $msg, $ex->getMessage(), $ex->getTraceAsString()),
            'pocketlists/error.log'
        );
    }
}
