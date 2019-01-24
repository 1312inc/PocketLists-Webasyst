<?php

//todo: save state

/**
 * Class pocketlistsRBAC
 */
class pocketlistsRBAC
{
    const RIGHT_NONE    = 0;
    const RIGHT_ACCESS  = 1;
    const RIGHT_ADMIN   = 2;
    const RIGHT_LIMITED = 3;

    const CAN_ASSIGN          = 'canassign';
    const CAN_CREATE_TODOS    = 'cancreatetodos';
    const CAN_USE_SHOP_SCRIPT = 'canuseshopscript';

    const POCKET_ITEM = 'pocket';
    const LIST_ITEM   = 'list';

    private static $access_rights = [];
    private static $lists         = [];
    private static $pockets       = [];

    /**
     * Return all list ids accessible for given user
     *
     * @param int $contact_id
     *
     * @return mixed
     * @throws waDbException
     * @throws waException
     */
    public static function getAccessListForContact($contact_id = 0)
    {
        $user = self::getContact($contact_id);
        $user_id = $user->getId();

        if (isset(self::$lists[$user_id])) {
            return array_keys(self::$lists[$user_id]);
        }

        $listModel = new pocketlistsListModel();

        if (self::isAdmin($contact_id)) {
            $lists = $listModel->getAll('id');
            if ($lists) {
                foreach ($lists as $list) {
                    self::addListUserRight($user_id, $list['id'], true);
                }
            }
        } else {
            $pockets = $user->getRights(pocketlistsHelper::APP_ID, self::POCKET_ITEM.'.%');
            foreach ($pockets as $pocketId => $rightValue) {
                self::addPocketUserRight($user_id, $pocketId, $rightValue);

                switch (true) {
                    // полный доступ к покету - возьмем все листы
                    case $rightValue == self::RIGHT_ADMIN:
                        $lists = $listModel->getAllLists(false, $pocketId);
                        foreach ($lists as $list) {
                            self::addListUserRight($user_id, $list->pk, true);
                        }
                        break;

                    // в другом случае либо нет доступа, либо доступ к листам сохраняется отдельно
                    case $rightValue == self::RIGHT_LIMITED:
                    case $rightValue == self::RIGHT_NONE:
                    default:
                        break;
                }
            }

            // соберем все остальные листы
            $accessedLists = $user->getRights(pocketlistsHelper::APP_ID, self::LIST_ITEM.'.%');
            if ($accessedLists) {
                foreach ($accessedLists as $accessedListId => $rightValue) {
                    self::addListUserRight($user_id, $accessedListId, true);
                }
            }
        }

        if (!isset(self::$lists[$user_id])) {
            self::$lists[$user_id] = [];
        }

        return array_keys(self::$lists[$user_id]);
    }

    /**
     * @param int $pocket_id
     * @param int $contact_id
     *
     * @return bool|int
     * @throws waDbException
     * @throws waException
     */
    public static function contactHasAccessToPocket($pocket_id, $contact_id = 0)
    {
        $user = self::getContact($contact_id);

        if (isset(self::$pockets[$user->getId()][$pocket_id])) {
            return self::$pockets[$user->getId()][$pocket_id];
        }

        self::fillPocketsForUser($user);

        return isset(self::$pockets[$user->getId()][$pocket_id]) ? self::$pockets[$user->getId()][$pocket_id] : false;
    }

    /**
     * @param int $contact_id
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    public static function getAccessPocketForContact($contact_id = 0)
    {
        $user = self::getContact($contact_id);

        if (!isset(self::$pockets[$user->getId()])) {
            self::fillPocketsForUser($user);
        }

        return array_keys(self::$pockets[$user->getId()]);
    }

    /**
     * Return users for given list
     *
     * @param pocketlistsListModel|null $list
     *
     * @return array
     */
    public static function getAccessContacts(pocketlistsListModel $list = null)
    {
        $wcr = new waContactRightsModel();
        $query = sprintf(
            "SELECT DISTINCT group_id FROM wa_contact_rights WHERE %s OR %s OR %s %s",
            self::haveFullAdminSQL(),
            self::haveFullAccessSQL(),
            $list ? self::haveAccessToListSQL($list) : self::haveAccessSQL(),
            $list && $list->pocket_id ? ' OR '.self::havePocketFullAccessSQL($list->pocket_id) : ''
        );

        $contact_ids = $wcr->query($query)->fetchAll();
        $contact_ids = array_unique(self::getContactIds($contact_ids));

        return $contact_ids;
    }

    /**
     * Check if user is admin
     *
     * @param bool|int $user
     *
     * @return bool
     * @throws waException
     */
    public static function isAdmin($user = false)
    {
        $contact = self::getContact($user);

        return $contact->isAdmin() || $contact->isAdmin(pocketlistsHelper::APP_ID);
    }

    public static function canAccessToList(pocketlistsListModel $list, $user_id = false)
    {
//        $user_id = $user_id ? $user_id : wa()->getUser()->getId();
//        if (!isset(self::$access_rights[$user_id])) {
//            self::$access_rights[$user_id] = self::getAccessListForContact($user_id);
//        }
        $user = self::getContact($user_id);

        if (isset(self::$lists[$user->getId()][$list->pk])) {
            return self::$lists[$user->getId()][$list->pk];
        }

        switch (true) {
            case $user->getRights(pocketlistsHelper::APP_ID, self::LIST_ITEM.'.'.$list->pk):
            case $list->pocket_id && $user->getRights(pocketlistsHelper::APP_ID, self::LIST_ITEM.'.'.$list->pocket_id):
                self::$lists[$user_id][$list->pk] = true;
                break;

            default:
                self::$lists[$user_id][$list->pk] = false;
        }

        return self::$lists[$user_id][$list->pk];
    }

    /**
     * @param bool|int|pocketlistsUser $user
     *
     * @return bool
     * @throws waException
     */
    public static function canAssign($user = false)
    {
        $contact = self::getContact($user);

        return $contact->getRights(pocketlistsHelper::APP_ID, self::CAN_ASSIGN);
    }

    /**
     * @param bool|int|pocketlistsUser $user
     *
     * @return bool
     * @throws waException
     */
    public static function canUseShopScript($user = false)
    {
        $contact = self::getContact($user);

        return $contact->getRights(pocketlistsHelper::APP_ID, self::CAN_USE_SHOP_SCRIPT);
    }

    /**
     * @param bool|int|pocketlistsUser $user
     *
     * @return bool
     * @throws waException
     */
    public static function canAccess($user = false)
    {
        $contact = self::getContact($user);

        return $contact->getRights(pocketlistsHelper::APP_ID, 'backend') === 1
            || $contact->getRights(pocketlistsHelper::APP_ID, self::CAN_CREATE_TODOS);
    }

    // todo: криво (sql код)

    /**
     * @param      $lists
     * @param bool $user_id
     *
     * @return array|string|null
     * @throws waDbException
     * @throws waException
     */
    public static function filterListAccess(&$lists, $user_id = false)
    {
        $user_id = $user_id ? (int)$user_id : wa()->getUser()->getId();
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
            $lists = self::$lists[$user_id] ? array_keys(self::$lists[$user_id]) : [];
        }

        if (empty($lists)) {
            $lists = [-1];
        }

        if (is_array($list_sql)) {
            $list_sql = '('.implode(' OR ', $list_sql).')';
        } elseif ($list_sql) {
            $list_sql = '('.$list_sql.')';
        } else {
            $list_sql = "(l.id IS NULL /* null list */ AND i.contact_id = {$user_id} /* other null lists */)";
        }

        return $list_sql;
    }

    /**
     * @param waContact $user
     *
     * @throws waDbException
     * @throws waException
     */
    private static function fillPocketsForUser(waContact $user)
    {
        $pocketModel = new pocketlistsPocketModel();

        if (self::isAdmin($user->getId())) {
            $pockets = $pocketModel->getAll('id');
            if ($pockets) {
                foreach ($pockets as $pocket) {
                    self::addPocketUserRight($user->getId(), $pocket['id'], self::RIGHT_ADMIN);
                }
            }
        } else {
            $pockets = $user->getRights(pocketlistsHelper::APP_ID, self::POCKET_ITEM.'.%');
            foreach ($pockets as $pocketId => $rightValue) {
                self::addPocketUserRight($user->getId(), $pocketId, (int)$rightValue);
            }
        }
    }

    /**
     * @param $userId
     * @param $pocketId
     * @param $rightValue
     */
    private static function addPocketUserRight($userId, $pocketId, $rightValue)
    {
        if (!isset(self::$pockets[$userId])) {
            self::$pockets[$userId] = [];
        }

        self::$pockets[$userId][$pocketId] = (int)$rightValue;
    }

    /**
     * @param $userId
     * @param $listId
     * @param $rightValue
     */
    private static function addListUserRight($userId, $listId, $rightValue)
    {
        if (!isset(self::$lists[$userId])) {
            self::$lists[$userId] = [];
        }

        self::$lists[$userId][$listId] = $rightValue;
    }

    /**
     * @return string
     */
    private static function haveFullAdminSQL()
    {
        return " (app_id = 'webasyst' AND name = 'backend' AND value = 1) ";
    }

    /**
     * @return string
     */
    private static function haveFullAccessSQL()
    {
        return sprintf(
            " (app_id = '%s' AND name = 'backend' AND value = %s)",
            pocketlistsHelper::APP_ID,
            self::RIGHT_ADMIN
        );
    }

    /**
     * @return string
     */
    private static function havePocketFullAccessSQL($pocket_id)
    {
        return sprintf(
            " (app_id = '%s' AND name = '%s.%s' AND value = %s)",
            pocketlistsHelper::APP_ID,
            self::POCKET_ITEM,
            $pocket_id,
            self::RIGHT_ADMIN
        );
    }

    /**
     * @return string
     */
    private static function haveAccessSQL()
    {
        return sprintf(
            " (app_id = '%s' AND name = 'backend' AND value = %s)",
            pocketlistsHelper::APP_ID,
            self::RIGHT_ACCESS
        );
    }

    /**
     * @param pocketlistsListModel|null $list
     *
     * @return string
     */
    private static function haveAccessToListSQL(pocketlistsListModel $list = null)
    {
        return sprintf(
            " (app_id = '%s' AND name = '%s.%s' AND value = %s)",
            pocketlistsHelper::APP_ID,
            self::LIST_ITEM,
            $list instanceOf pocketlistsListModel ? $list->pk : '%',
            self::RIGHT_ACCESS
        );
    }

    /**
     * @param array $contact_ids
     *
     * @return array
     */
    private static function getContactIds($contact_ids)
    {
        $contacts = [];
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
     * @param $user
     *
     * @return waAuthUser|waContact|waUser
     * @throws waException
     */
    private static function getContact($user)
    {
        if (!$user instanceof pocketlistsUser) {
            $contact = $user && is_int($user) ? new waContact($user) : wa()->getUser();
        } else {
            $contact = $user->getContact();
        }

        return $contact;
    }
}
