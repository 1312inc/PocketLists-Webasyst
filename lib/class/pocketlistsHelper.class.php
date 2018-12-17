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

    public static function getMonthData($items, $show_month, $month_count = 1)
    {
        $timezone = wa()->getUser()->getTimezone();
        $month_date = waDateTime::date('Y-m', null, $timezone);
        if ($show_month) {
            $month_date = date('Y-m', strtotime(date('Y-m-01') . ' ' . ($show_month . ' month')));
        }
        $month_date = strtotime($month_date);

        $list_colors = array();
        // completed items
        foreach ($items[1] as $item) {
            $list_colors[date('Y-m-d', strtotime($item['complete_datetime']))]['gray'][] = $item['id'];
        }
        foreach ($items[0] as $item) {
            if ($item['due_datetime'] || $item['due_date']) {
                $due_date = date('Y-m-d', strtotime($item['due_date'] ? $item['due_date'] : $item['due_datetime']));
                $item['list_color'] = $item['list_color'] ? $item['list_color'] : 'gray';
                $list_colors[$due_date]['color'][$item['list_color']][] = $item['id'];
            }
        }

        $days = array();
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
            $date_start = strtotime('-' . $first_day . ' days', $month_date);
            $date_end = strtotime('+' . ($days_count + $last_day) . ' days', $month_date);

            $current_date_start = $date_start;
            $year = date('Y', $month_date);
            $month_name = date('F', $month_date);
            $month_num = (int)date('n', $month_date);
            $days[$year][$month_name] = array(
                'weeks' => array(),
                'num'   => $month_num,
            );

            do {
                $week = (int)date('W', $current_date_start);
                $day = (int)date('w', $current_date_start);

                if (waLocale::getFirstDay() == 7 && $day == 0) {
                    $week = (int)date('W', strtotime('+1 week', $current_date_start));
                }

                if (!isset($days[$year][$month_name]['weeks'][$week])) {
                    $days[$year][$month_name]['days'][$week] = array();
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
                $days[$year][$month_name]['weeks'][$week][$day] = array(
                    'date'  => array(
                        'day'   => date('j', $current_date_start),
                        'month' => $current_month,
                        'date'  => $date_date,
                    ),
                    'hide'  => $hide_other_month_date,
                    'lists' => array(
                        'color' => isset($list_colors[$date_date]['color']) ?
                            $list_colors[$date_date]['color'] : array(),
                        'gray'  => isset($list_colors[$date_date]['gray']) ?
                            $list_colors[$date_date]['gray'] : array(),
                    ),
//                        isset($list_colors[$date_date]) ? array_keys($list_colors[$date_date]) : array()
                );
                $current_date_start = strtotime('+1 days', $current_date_start);
            } while ($date_end > $current_date_start);

            $month_date = strtotime('+1 month', $month_date);
        }

        return array(
            'days'       => $days,
            'month_date' => $month_date,
        );
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

    public static function getAccessContactsForPocket($pocket_id = false, $photo_size = 20)
    {
        $wcr = new waContactRightsModel();
        // select users
        $query = "SELECT DISTINCT
                group_id
            FROM wa_contact_rights
            WHERE
              (app_id = 'pocketlists' AND ((name LIKE s:id AND value = 1) OR (name = 'backend' AND value = 2))
              OR
              (app_id = 'webasyst' AND name = 'backend' AND value = 1))";
        $contact_ids = $wcr->query(
            $query,
            array(
                'id' => 'pocket.' . ($pocket_id ? $pocket_id : '%')
            )
        )->fetchAll();
        $contacts = array();
        $groups = array();
        $gm = new waUserGroupsModel();
        foreach ($contact_ids as $id) {
            if ($id['group_id'] < 0) {
                $contact = new waContact(-$id['group_id']);
                $contacts[$contact->getId()] = array(
                    'username' => $contact->getName(),
                    'userpic' => $contact->getPhoto($photo_size)
                );
            } else {
                $groups[] = $id['group_id'];
            }
        }
        $contact_ids = array();
        foreach ($groups as $group_id) {
            $contact_ids = array_merge($contact_ids, $gm->getContactIds($group_id));
        }
        foreach ($contact_ids as $id) {
            $contact = new waContact($id);
            if (!isset($contacts[$contact->getId()])) {
                $contacts[$contact->getId()] = array(
                    'username' => $contact->getName(),
                    'userpic' => $contact->getPhoto($photo_size)
                );
            }
        }
        return $contacts;
    }

    public static function getAccessPocketForContact($contact_id = false)
    {
        $user = $contact_id ? new waContact($contact_id) : wa()->getUser();
        if ($user->isAdmin() || $user->isAdmin('pocketlists')) {
            $pm = new pocketlistsPocketModel();
            $pockets = $pm->getAll('id');
        } else {
            $pockets = $user->getRights('pocketlists', 'pocket.%');

        }
        return $pockets ? array_keys($pockets) : [];
    }

    public static function getAllPocketListsContacts()
    {
        $wcr = new waContactRightsModel();
        $query = "SELECT DISTINCT
                group_id
            FROM wa_contact_rights
            WHERE
              (app_id = 'pocketlists' AND name = 'backend' AND value > 0)
              OR
              (app_id = 'webasyst' AND name = 'backend' AND value > 0)
            ORDER BY group_id ASC";
        $contact_ids = $wcr->query($query)->fetchAll();
        $contacts = array();
        $groups = array();
        $gm = new waUserGroupsModel();
        foreach ($contact_ids as $id) {
            if ($id['group_id'] < 0) { // user
                $contacts[-$id['group_id']] = -$id['group_id'];
            } else { // group
                $groups[] = $id['group_id'];
            }
        }
        $contact_ids = array();
        foreach ($groups as $group_id) {
            $contact_ids = array_merge($contact_ids, $gm->getContactIds($group_id));
        }
        foreach ($contact_ids as $id) {
            $contacts[$id] = $id;
        }
        return $contacts;
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
}
