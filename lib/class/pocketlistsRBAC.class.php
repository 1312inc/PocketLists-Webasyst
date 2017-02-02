<?php

class pocketlistsRBAC
{
    private static $access_rights = array();

    /**
     * Return users for given list
     * @param bool|integer $list_id
     * @param int $photo_size
     * @return array
     */
    public static function getAccessContactsForList($list_id = false, $photo_size = 20)
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
                'id' => 'list.' . ($list_id ? $list_id : '%'),
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
                    'userpic'  => $contact->getPhoto($photo_size),
                    'login'    => $contact['login'],
                    'status'   => $contact->getStatus(),
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
                    'userpic'  => $contact->getPhoto($photo_size),
                    'login'    => $contact['login'],
                    'status'   => $contact->getStatus(),
                );
            }
        }
        return $contacts;
    }

    /**
     * Return all list ids accessible for given user
     * @param bool|integer $contact_id
     * @return array|bool
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
        return $lists ? array_keys($lists) : false;
    }

    public static function canAccessToList($list_id, $user_id = false)
    {
        $user_id = $user_id ? $user_id : wa()->getUser()->getId();
        if (!isset(self::$access_rights[$user_id])) {
            self::$access_rights[$user_id] = self::getAccessListForContact($user_id);
        }

        return in_array($list_id, self::$access_rights[$user_id]);

    }

    /**
     * @return array
     */
    public static function getAllListsContacts()
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
     * Check if user is admin
     * @param bool|integer $contact_id
     * @return bool
     */
    public static function isAdmin($contact_id = false)
    {
        $user = $contact_id ? new waContact($contact_id) : wa()->getUser();
        $result = $user->isAdmin() || $user->isAdmin('pocketlists');
        return $result;
    }

    public static function canCreateLists()
    {
        return wa()->getUser()->getRights(pocketlistsHelper::APP_ID, 'cancreatelists');
    }

    public static function canAssign()
    {
        return wa()->getUser()->getRights(pocketlistsHelper::APP_ID, 'canassign');
    }
}
