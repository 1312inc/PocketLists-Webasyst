<?php

class pocketlistsRBAC
{
    private static $access_rights = array();

    /**
     * Return all list ids accessible for given user
     * @param bool|integer $contact_id
     * @return array
     */
    public static function getAccessListForContact($contact_id = false)
    {
        $user = $contact_id ? new waContact($contact_id) : wa()->getUser();
        if ($user->isAdmin() || $user->isAdmin('pocketlists')) {
            $list_model = new pocketlistsListModel();
            $lists = $list_model->getAll('id');
        } else {
            $lists = $user->getRights('pocketlists', 'list.%');
        }
        // todo: может сразу возвращать модели?
        return $lists ? array_keys($lists) : array();
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

        $contact_ids = self::getContactIds($contact_ids);
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
}
