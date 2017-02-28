<?php

//todo: save state
class pocketlistsRBAC
{
    const ADMIN_VALUE = 2;
    const ACCESS_VALUE = 1;

    private static $access_rights = array();
    private static $lists = array();

    /**
     * Return all list ids accessible for given user
     * @param bool|integer $contact_id
     * @return array
     */
    public static function getAccessListForContact($contact_id = false)
    {
        $user = $contact_id ? new waContact($contact_id) : wa()->getUser();
        $user_id = $user->getId();

        if (isset(self::$lists[$user_id])) {
            return self::$lists[$user_id];
        }

        if (self::isAdmin($contact_id)) {
            $list_model = new pocketlistsListModel();
            self::$lists[$user_id] = $list_model->getAll('id');
        } else {
            self::$lists[$user_id] = $user->getRights('pocketlists', 'list.%');
        }
        // todo: может сразу возвращать модели?
        self::$lists[$user_id] = self::$lists[$user_id] ? array_keys(self::$lists[$user_id]) : array();
        return self::$lists[$user_id];
    }


    private static function haveFullAdminSQL()
    {
        return " (app_id = 'webasyst' AND name = 'backend' AND value = 1) ";
    }

    private static function haveFullAccessSQL()
    {
        return " (app_id = '" . pocketlistsHelper::APP_ID . "' AND name = 'backend' AND value = 2) ";
    }

    private static function haveAccessSQL()
    {
        return " (app_id = '" . pocketlistsHelper::APP_ID . "' AND name = 'backend' AND value = 1) ";
    }

    private static function haveAccessToListSQL($list_id = 0)
    {
        return " (app_id = '" . pocketlistsHelper::APP_ID . "' AND name = 'list." . ($list_id ? (int)$list_id : '%') . "' AND value = 1) ";
    }

    private static function getContactIds($contact_ids)
    {
        $contacts = array();
        $gm = new waUserGroupsModel();
        foreach ($contact_ids as $id) {
            if ($id['group_id'] < 0) { // user
                $contacts[] = -$id['group_id'];
            } else { // group
                $contacts = array_merge($contacts, $gm->getContactIds($id['group_id']));
            }
        }
        return $contacts;
    }

    /**
     * Return users for given list
     * @param integer $list_id
     * @param int $photo_size
     * @return array
     */
    public static function getAccessContacts($list_id = 0)
    {
        $wcr = new waContactRightsModel();
        $query = "
            SELECT DISTINCT
                group_id
            FROM wa_contact_rights
            WHERE "
            . self::haveFullAdminSQL()
            . " OR "
            . self::haveFullAccessSQL()
            . " OR "
            . ($list_id ? self::haveAccessToListSQL($list_id) : self::haveAccessSQL());
        $contact_ids = $wcr->query($query)->fetchAll();

        $contact_ids = array_unique(self::getContactIds($contact_ids));
        return $contact_ids;
    }

    /**
     * Check if user is admin
     * @param bool|integer $contact_id
     * @return bool
     */
    public static function isAdmin($user_id = false)
    {
        $user = $user_id ? new waContact($user_id) : wa()->getUser();
        return $user->isAdmin() || $user->isAdmin('pocketlists');
    }

    public static function canAccessToList($list_id, $user_id = false)
    {
//        $user_id = $user_id ? $user_id : wa()->getUser()->getId();
//        if (!isset(self::$access_rights[$user_id])) {
//            self::$access_rights[$user_id] = self::getAccessListForContact($user_id);
//        }
        $user = $user_id ? new waContact($user_id) : wa()->getUser();
        if ($user->getRights(pocketlistsHelper::APP_ID, 'list.' . $list_id)) {
            self::$access_rights[$user_id][$list_id] = true;
            return true;
        }
        return false;

    }

    public static function canCreateLists($user_id = false)
    {
        $user = $user_id ? new waContact($user_id) : wa()->getUser();
        return $user->getRights(pocketlistsHelper::APP_ID, 'cancreatelists');
    }

    public static function canAssign($user_id = false)
    {
        $user = $user_id ? new waContact($user_id) : wa()->getUser();
        return $user->getRights(pocketlistsHelper::APP_ID, 'canassign');
    }

    public static function canAccess($user_id = false)
    {
        $user = $user_id ? new waContact($user_id) : wa()->getUser();
        return $user->getRights(pocketlistsHelper::APP_ID, 'backend') === 1 || $user->getRights(pocketlistsHelper::APP_ID, 'cancreatetodos');
    }

    // todo: криво (sql код)
    public static function filterListAccess(&$lists, $user_id = false)
    {
        $user_id = $user_id ? (int) $user_id : wa()->getUser()->getId();
        $list_sql = null;
        $lists_user = self::getAccessListForContact($user_id);
        if (!self::isAdmin($user_id)) {
//            if (self::canAccess()) {
//            }
            if ($lists_user) {
                $list_sql[] = "(l.id IN (i:list_ids) /* accessed lists*/)";
            }
            if (self::canAssign($user_id)) {
                $list_sql[] = "(l.id IS NULL /* null list */)";
            }
        } else {
            $list_sql = '1';
        }

        if (is_array($lists) && !empty($lists)) {
            $lists = array_intersect($lists_user, $lists);
        } else {
            $lists = self::$lists[$user_id];
        }

        if (empty($lists)) {
            $lists = array(-1);
        }

        if (is_array($list_sql)) {
            $list_sql = '(' . implode(' OR ', $list_sql) . ')';
        } elseif ($list_sql) {
            $list_sql = '(' . $list_sql . ')';
        } else {
            $list_sql = "(l.id IS NULL /* null list */ AND i.contact_id = {$user_id} /* other null lists */)";
        }

        return $list_sql;
    }
}
