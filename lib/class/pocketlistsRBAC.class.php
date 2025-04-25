<?php

/**
 * Class pocketlistsRBAC
 */
class pocketlistsRBAC
{
    public const RIGHT_NONE        = 0;
    public const RIGHT_ACCESS      = 1;
    public const RIGHT_FULL_ACCESS = 2;
    public const RIGHT_LIMITED     = 3;
    public const RIGHT_ADMIN       = 99;

    public const CAN_ASSIGN          = 'canassign';
    public const CAN_CREATE_TODOS    = 'cancreatetodos';
    public const CAN_USE_SHOP_SCRIPT = 'canuseshopscript';

    public const POCKET_ITEM = 'pocket';
    public const LIST_ITEM   = 'list';

    private static $lists   = [];
    private static $pockets = [];

    /**
     * Return all list ids accessible for given user
     *
     * @param int $contact_id
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    public static function getAccessListForContact($contact_id = 0): array
    {
        $user = self::getContact($contact_id);
        $user_id = $user->getId();

        if (!isset(self::$lists[$user_id])) {
            self::$lists[$user_id] = [];

            /** @var pocketlistsListModel $listModel */
            $listModel = pl2()->getModel(pocketlistsList::class);

            if (self::isAdmin($user_id)) {
                $lists = $listModel->query("
                    SELECT pl.*, pi2.contact_id FROM pocketlists_list pl 
                    LEFT JOIN pocketlists_item pi2 ON pi2.key_list_id = pl.id
                ")->fetchAll('id');

                if ($lists) {
                    foreach ($lists as $list) {
                        if ($list['private'] && $user_id != $list['contact_id']) {
                            continue;
                        }
                        self::addListUserRight($user_id, $list['id'], true);
                    }
                }
            } else {
                self::addPocketUserRight($user_id, 0, self::RIGHT_NONE);
                $pockets = $user->getRights(pocketlistsHelper::APP_ID, self::POCKET_ITEM . '.%');
                foreach ($pockets as $pocketId => $rightValue) {
                    self::addPocketUserRight($user_id, $pocketId, $rightValue);

                    switch (true) {
                        // полный доступ к покету - возьмем все листы
                        case $rightValue == self::RIGHT_ADMIN:
                            $lists = $listModel->getAllLists(false, $pocketId);
                            foreach ($lists as $list) {
                                self::addListUserRight($user_id, $list['id'], true);
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
                self::addListUserRight($user_id, 0, true);
                $accessedLists = $user->getRights(pocketlistsHelper::APP_ID, self::LIST_ITEM . '.%');
                if ($accessedLists) {
                    foreach ($accessedLists as $accessedListId => $rightValue) {
                        self::addListUserRight($user_id, $accessedListId, true);
                    }
                }
            }
        }

        return array_filter(array_keys(self::$lists[$user_id]));
    }

    /**
     * @param pocketlistsPocket       $pocket
     * @param pocketlistsContact|null $contact
     *
     * @return bool
     * @throws waDbException
     * @throws waException
     */
    public static function contactHasAccessToPocket(pocketlistsPocket $pocket, pocketlistsContact $contact = null)
    {
        $user = self::getContact($contact);

        if (isset(self::$pockets[$user->getId()][$pocket->getId()])) {
            return self::$pockets[$user->getId()][$pocket->getId()];
        }

        self::fillPocketsForUser($user);

        return isset(self::$pockets[$user->getId()][$pocket->getId()])
            ? self::$pockets[$user->getId()][$pocket->getId()]
            : false;
    }

    /**
     * @param pocketlistsContact|null|int $contact
     *
     * @return array
     * @throws waDbException
     * @throws waException
     */
    public static function getAccessPocketForContact($contact = null)
    {
        $user = self::getContact($contact);

        if (!isset(self::$pockets[$user->getId()])) {
            self::fillPocketsForUser($user);
        }

        return array_key_exists($user->getId(), self::$pockets) ? array_keys(self::$pockets[$user->getId()]) : [];
    }

    /**
     * Return users for given list
     *
     * @param pocketlistsList|array $list
     *
     * @return array
     */
    public static function getAccessContacts($list = null): array
    {
        if ($list instanceof pocketlistsList) {
            $list_id = $list->getId();
            $pocket_id = $list->getPocketId();
        } else {
            $list_id = ifempty($list, 'id', null);
            $pocket_id = ifempty($list, 'pocket_id', null);
        }
        $wcr = new waContactRightsModel();
        $query = sprintf(
            "SELECT DISTINCT group_id FROM wa_contact_rights WHERE %s OR %s OR %s %s",
            self::haveFullAdminSQL(),
            self::haveFullAccessSQL(),
            $list_id ? self::haveAccessToListSQL($list_id) : self::haveAccessSQL(),
            $pocket_id ? ' OR '.self::havePocketFullAccessSQL($pocket_id) : ''
        );

        $contact_ids = $wcr->query($query)->fetchAll();
        $contact_ids = array_unique(self::getContactIds($contact_ids));

        return $contact_ids;
    }

    public static function getAccessContactsByPockets($pocket_ids = [])
    {
        $result = [];
        $groups = [];
        if (empty($pocket_ids)) {
            return $result;
        }

        $wa_model = new waModel();
        $r_pockets = implode("','", array_map(function ($p) {return self::POCKET_ITEM.".$p";}, $pocket_ids));
        $contact_rights = $wa_model->query(sprintf(
            "SELECT group_id, name FROM wa_contact_rights WHERE %s OR %s OR %s GROUP BY group_id, name",
            self::haveFullAdminSQL(),
            self::haveFullAccessSQL(),
            "(app_id = 'pocketlists' AND name IN ('$r_pockets') AND value IN (".implode(',', [self::RIGHT_ADMIN, self::RIGHT_LIMITED])."))"
        ))->fetchAll();

        foreach ($contact_rights as $contact_right) {
            if ($contact_right['group_id'] < 0) {
                if ($contact_right['name'] == 'backend') {
                    foreach ($pocket_ids as $pocket_id) {
                        $result[$pocket_id][-$contact_right['group_id']] = 1;
                    }
                } elseif ($pocket_id = (int) str_replace(pocketlistsRBAC::POCKET_ITEM.'.', '', $contact_right['name'])) {
                    $result[$pocket_id][-$contact_right['group_id']] = 1;
                }
            } else {
                $groups[$contact_right['group_id']] = [
                    'group_id' => 0,
                    'name' => $contact_right['name']
                ];
            }
        }
        unset($contact_rights, $contact_right);

        if ($groups) {
            $user_groups = $wa_model->query("
                SELECT group_id, contact_id FROM wa_user_groups
                WHERE group_id IN (i:g_ids)
                GROUP BY group_id, contact_id
                ORDER BY group_id 
            ", ['g_ids' => array_keys($groups)])->fetchAll();
            foreach ($groups as $group_id => $group) {
                foreach ($user_groups as $user_group) {
                    if ($group_id == $user_group['group_id']) {
                        if ($group['name'] == 'backend') {
                            foreach ($pocket_ids as $pocket_id) {
                                $result[$pocket_id][$user_group['contact_id']] = 1;
                            }
                        } elseif ($pocket_id = (int) str_replace(pocketlistsRBAC::POCKET_ITEM.'.', '', $group['name'])) {
                            $result[$pocket_id][$user_group['contact_id']] = 1;
                        }
                    }
                }
            }
            unset($groups, $group);
        }

        return array_map(function ($r) {return array_keys($r);}, $result);
    }

    public static function getAccessContactsByLists($list_ids = [])
    {
        $result = [];
        $groups = [];
        $pockets = [];
        if (empty($list_ids)) {
            return $result;
        }
        $wa_model = new waModel();
        $r_lists = implode("','", array_map(function ($l) {return self::LIST_ITEM.".$l";}, $list_ids));
        $contact_rights = $wa_model->query(sprintf(
            "SELECT group_id, name FROM wa_contact_rights WHERE %s OR %s OR %s OR %s GROUP BY group_id, name",
            self::haveFullAdminSQL(),
            self::haveFullAccessSQL(),
            "(app_id = 'pocketlists' AND name LIKE '".self::POCKET_ITEM.".%' AND value = ".self::RIGHT_ADMIN.")",
            "(app_id = 'pocketlists' AND name IN ('$r_lists') AND value = ".self::RIGHT_ACCESS.")"
        ))->fetchAll();
        foreach ($contact_rights as $contact_right) {
            if ($contact_right['group_id'] < 0) {
                if ($contact_right['name'] == 'backend') {
                    foreach ($list_ids as $list_id) {
                        $result[$list_id][-$contact_right['group_id']] = 1;
                    }
                } elseif ($list_id = (int) str_replace(pocketlistsRBAC::LIST_ITEM.'.', '', $contact_right['name'])) {
                    $result[$list_id][-$contact_right['group_id']] = 1;
                } elseif ($pocket_id = (int) str_replace(pocketlistsRBAC::POCKET_ITEM.'.', '', $contact_right['name'])) {
                    $pockets[$pocket_id][-$contact_right['group_id']] = 1;
                }
            } else {
                $groups[$contact_right['group_id']] = [
                    'group_id' => 0,
                    'name' => $contact_right['name']
                ];
            }
        }
        unset($contact_rights, $contact_right);
        if ($groups) {
            $user_groups = $wa_model->query("
                SELECT group_id, contact_id FROM wa_user_groups
                WHERE group_id IN (i:g_ids)
                GROUP BY group_id, contact_id
                ORDER BY group_id 
            ", ['g_ids' => array_keys($groups)])->fetchAll();
            foreach ($groups as $group_id => $group) {
                foreach ($user_groups as $user_group) {
                    if ($group_id == $user_group['group_id']) {
                        if ($group['name'] == 'backend') {
                            foreach ($list_ids as $list_id) {
                                $result[$list_id][$user_group['contact_id']] = 1;
                            }
                        } elseif ($list_id = (int) str_replace(pocketlistsRBAC::LIST_ITEM.'.', '', $group['name'])) {
                            $result[$list_id][$user_group['contact_id']] = 1;
                        } elseif ($pocket_id = (int) str_replace(pocketlistsRBAC::POCKET_ITEM.'.', '', $group['name'])) {
                            $pockets[$pocket_id][$user_group['contact_id']] = 1;
                        }
                    }
                }
            }
            unset($groups, $group);
        }
        if ($pockets) {
            $by_pockets = [];
            $right_by_pocket = $wa_model->query("
                SELECT pocket_id, id FROM pocketlists_list
                WHERE pocket_id IN (i:p_ids)
                AND id IN (i:l_ids)
                GROUP BY pocket_id, id
                ORDER BY pocket_id
            ", ['l_ids' => $list_ids, 'p_ids' => array_keys($pockets)])->fetchAll();
            foreach ($right_by_pocket as $_by_pocket) {
                $by_pockets[$_by_pocket['pocket_id']][$_by_pocket['id']] = 1;
            }
            foreach ($by_pockets as $p_id => $_by_pocket) {
                $contact_ids = ifset($pockets, $p_id, []);
                foreach (array_keys($_by_pocket) as $key) {
                    $result[$key] += $contact_ids;
                }
            }
            unset($pockets, $by_pockets, $right_by_pocket);
        }
        $private_lists = $wa_model->query("
            SELECT pl.id, pi2.contact_id FROM pocketlists_list pl 
            LEFT JOIN pocketlists_item pi2 ON pi2.key_list_id = pl.id
            WHERE pl.private = 1 AND pl.id IN (i:l_ids)
        ", ['l_ids' => $list_ids])->fetchAll();
        if ($private_lists) {
            foreach ($private_lists as $private_list) {
                if ($result[$private_list['id']]) {
                    $result[$private_list['id']] = [$private_list['contact_id'] => 1];
                }
            }
        }

        return array_map(function ($r) {return array_keys($r);}, $result);
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

    public static function canAccessToList(pocketlistsList $list, $user_id = false)
    {
        $user = self::getContact($user_id);

        if (isset(self::$lists[$user->getId()][$list->getId()])) {
            return self::$lists[$user->getId()][$list->getId()];
        }

        switch (true) {
            case $user->getRights(pocketlistsHelper::APP_ID, self::LIST_ITEM . '.' . $list->getId()):
            case $list->getPocketId()
                && $user->getRights(
                    pocketlistsHelper::APP_ID,
                    self::POCKET_ITEM . '.' . $list->getPocketId()
                ) == self::RIGHT_ADMIN:
                self::addListUserRight($user_id, $list->getId(), true);
                break;

            default:
                self::addListUserRight($user_id, $list->getId(), false);
        }

        return self::$lists[$user_id][$list->getId()];
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

    public static function getShopScriptUsers()
    {

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
        $user_id = $user_id ? (int) $user_id : wa()->getUser()->getId();
        $list_sql = null;
        $lists_user = self::getAccessListForContact($user_id);
        if (!self::isAdmin($user_id)) {
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
            $list_sql = '(' . implode(' OR ', $list_sql) . ')';
        } elseif ($list_sql) {
            $list_sql = '(' . $list_sql . ')';
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
            $pockets = $user->getRights(pocketlistsHelper::APP_ID, self::POCKET_ITEM . '.%');
            foreach ($pockets as $pocketId => $rightValue) {
                self::addPocketUserRight($user->getId(), $pocketId, (int) $rightValue);
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

        self::$pockets[$userId][$pocketId] = (int) $rightValue;
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
        return " (app_id = 'webasyst' AND name = 'backend' AND value in (1,2)) ";
    }

    /**
     * @return string
     */
    private static function haveFullAccessSQL(): string
    {
        return sprintf(
            " (app_id = '%s' AND name = 'backend' AND value = %s)",
            pocketlistsHelper::APP_ID,
            self::RIGHT_FULL_ACCESS
        );
    }

    private static function havePocketFullAccessSQL(int $pocket_id): string
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
     * @param int|null $list_id
     *
     * @return string
     */
    private static function haveAccessToListSQL($list_id = null)
    {
        return sprintf(
            " (app_id = '%s' AND name = '%s.%s' AND value = %s)",
            pocketlistsHelper::APP_ID,
            self::LIST_ITEM,
            $list_id ?: '%',
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
     * @param pocketlistsContact|int $user
     *
     * @return waAuthUser|waContact|waUser
     * @throws waException
     */
    private static function getContact($user)
    {
        if (!$user instanceof pocketlistsContact) {
            $contact = $user && is_int($user) ? new waContact($user) : wa()->getUser();
        } else {
            $contact = $user->getContact();
        }

        return $contact;
    }

    /**
     * @param $user_id
     * @return array
     * @throws waException
     */
    public static function getUserRights($user_id = null)
    {
        if (empty($user_id)) {
            $user_id = wa()->getUser()->getId();
        }
        $cr_model = new waContactRightsModel();
        $raw_rights = $cr_model->get($user_id, pocketlistsHelper::APP_ID);
        $r_backend = (int) ifset($raw_rights, 'backend', self::RIGHT_NONE);

        if (empty($r_backend)) {
            $rights['backend'] = 'NONE';
        } elseif ($r_backend === 1) {
            $pockets = [];
            $lists = [];
            foreach ((array) $raw_rights as $key => $_right) {
                $_explode = explode('.', $key);
                if (count($_explode) === 2) {
                    if ($_explode[0] === 'list') {
                        $lists[] = [
                            'list_id' => (int) $_explode[1],
                            'right'   => 'FULL'
                        ];
                    } elseif ($_explode[0] === 'pocket') {
                        $pockets[] = [
                            'pocket_id' => (int) $_explode[1],
                            'right'     => ($_right == self::RIGHT_ADMIN ? 'FULL' : 'LIMITED')
                        ];
                    }
                }
            }
            $rights = [
                'backend'          => 'LIMITED',
                'cancreatetodos'   => (int) ifset($raw_rights, 'cancreatetodos', self::RIGHT_NONE),
                'canassign'        => (int) ifset($raw_rights, 'canassign', self::RIGHT_NONE),
                'canuseshopscript' => (int) ifset($raw_rights, 'canuseshopscript', self::RIGHT_NONE),
                'pockets'          => $pockets,
                'lists'            => $lists
            ];
        } else {
            $rights['backend'] = 'FULL';
        }

        return $rights;
    }
}
