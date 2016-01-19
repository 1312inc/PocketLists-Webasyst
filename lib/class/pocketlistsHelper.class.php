<?php

class pocketlistsHelper
{
    public static function getAccessContactsForPocket($pocket_id = false)
    {
        $wcr = new waContactRightsModel();
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
        foreach ($contact_ids as $id) {
            $contact = new waContact(-$id['group_id']);
            $contacts[$contact->getId()] = array(
                'username' => $contact->getName(),
                'userpic' => $contact->getPhoto(20)
            );
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
        return $pockets ? array_keys($pockets) : false;
    }

    public static function getAllPocketListsContacts()
    {
        $wcr = new waContactRightsModel();
        $query = "SELECT DISTINCT
                -1*group_id user_id
            FROM wa_contact_rights
            WHERE
              (app_id = 'pocketlists' AND name = 'backend' AND value > 0)
              OR
              (app_id = 'webasyst' AND name = 'backend' AND value > 0)
            ORDER BY user_id ASC";
        return array_keys($wcr->query($query)->fetchAll('user_id'));
    }

    public static function isAdmin()
    {
        static $result = null;
        if ($result === null) {
            $result = wa()->getUser()->getRights('pocketlists', 'backend') > 1;
        }
        return $result;
    }

    public static function getDueDatetime(&$date)
    {
        if (!empty($date['due_date']) &&
            !empty($date['due_datetime_hours']) &&
            !empty($date['due_datetime_minutes'])
        ) {
            $date['due_datetime'] = waDateTime::parse(
                'datetime',
                $date['due_date'] . " " . $date['due_datetime_hours'] . ":" . $date['due_datetime_minutes'] . ":00"
            );
            unset($date['due_datetime_hours']);
            unset($date['due_datetime_minutes']);
        } else {
            $date['due_datetime'] = null;
        }

        $date['due_date'] = !empty($date['due_date']) ? waDateTime::parse('date', waDateTime::format('date', $date['due_date'])) : null;
    }

    public static function calcPriorityOnDueDate($due_date, $due_datetime)
    {
        $now = time();
        $due_status = 0;

        if (!empty($due_date) || !empty($due_datetime)) {
            if (!empty($due_datetime) && $now > strtotime($due_datetime)) { // overdue datetime
                $due_status = 3;
            } elseif (strtotime(date("Y-m-d")) > strtotime($due_date)) { // overdue date
                $due_status = 3;
            } elseif ($due_date == date("Y-m-d")) { // today
                $due_status = 2;
            } elseif ($due_date == date("Y-m-d", $now + 60 * 60 * 24)) { // tomorrow
                $due_status = 1;
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
}
