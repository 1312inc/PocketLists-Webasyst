<?php

/**
 * Class pocketlistsRightConfig
 */
class pocketlistsRightConfig extends waRightConfig
{
    /**
     * @var int
     */
    private $userId;


    /**
     * @var waContactRightsModel
     */
    private $right_model;

    private static $begin_rights = [
        'list'   => [],
        'pocket' => [],
    ];

    /**
     * @var array
     */
    private static $rightsCache = [
        'list'   => [],
        'pocket' => [],
    ];

    /**
     * pocketlistsRightConfig constructor.
     */
    public function __construct()
    {
        $this->right_model = new waContactRightsModel();
        $this->userId = waRequest::post('user_id', 0, waRequest::TYPE_INT);

        if (!$this->userId) {
            $this->userId = waRequest::request('id', 0, waRequest::TYPE_INT);
        }
        $this->setBeginRights();

        parent::__construct();
    }

    public function __destruct()
    {
        if (!empty(self::$rightsCache['pocket']) && $ids = $this->getDiff(pocketlistsRBAC::POCKET_ITEM)) {
            /** pockets */
            $this->right_model->exec('
                UPDATE pocketlists_pocket
                SET activity_datetime = s:act_dt
                WHERE id IN (i:ids)
            ', [
                'act_dt' => date('Y-m-d H:i:s'),
                'ids'    => $ids,
            ]);
        }
        if (!empty(self::$rightsCache['list']) && $ids = $this->getDiff(pocketlistsRBAC::LIST_ITEM)) {
            /** lists */
            $this->right_model->exec('
                UPDATE pocketlists_item
                SET activity_datetime = s:act_dt
                WHERE key_list_id IN (i:ids) AND status = 0
            ', [
                'act_dt' => date('Y-m-d H:i:s'),
                'ids'    => $ids,
            ]);
        }
        $this->saveLog();
    }

    /**
     * @throws waException
     */
    public function init()
    {
        $this->addItem(pocketlistsRBAC::CAN_CREATE_TODOS, _w('Can access shared lists & create to-dos to self'), 'always_enabled');
        $this->addItem(
            pocketlistsRBAC::CAN_ASSIGN,
            _w('Can see other users personal to-dos and assign to-dos to teammates'),
            //_w('Can see Shop-Script to-dos, other users personal to-dos, and assign to-dos to teammates'),
            'checkbox'
        );
        $this->addItem(
            pocketlistsRBAC::CAN_USE_SHOP_SCRIPT,
            _w('Can create and see to-dos linked with Shop-Script orders'),
            'checkbox'
        );

        // POCKETS

        $items = [];
        /** @var pocketlistsPocketFactory $pocketFactory */
        $pocketFactory = pl2()->getEntityFactory(pocketlistsPocket::class);
        foreach ($pocketFactory->findAllForUser() as $pocket) {
            $items[$pocket->getId()] = $pocket->getName();
        }

        $this->addItem(
            pocketlistsRBAC::POCKET_ITEM,
            _w('Pockets'),
            'selectlist',
            [
                'items'    => $items,
                'position' => 'right',
                'options'  => [
                    pocketlistsRBAC::RIGHT_NONE    => _w('No access'),
                    pocketlistsRBAC::RIGHT_LIMITED => _w('Limited access'),
                    pocketlistsRBAC::RIGHT_ADMIN   => _w('Full access'),
                ],
            ]
        );

        $rights = (new waContactRightsModel())->get($this->userId, pocketlistsHelper::APP_ID);
        $currentPocketRights = [];
        foreach ($rights as $right => $rightValue) {
            if (strpos($right, pocketlistsRBAC::POCKET_ITEM.'.') !== 0) {
                continue;
            }

            $currentPocketRights[str_replace(pocketlistsRBAC::POCKET_ITEM.'.', '', $right)] = $rightValue;
        }
        // LISTS

        if (empty($rights)) {
            $this->addItem(
                '',
                _w('To setup access rights by pocket, set Limited access for at least one pocket'),
                'header',
                ['hint1' => 'Подсказка']
            );
        } else {
            /** @var pocketlistsListFactory $listFactory */
            $listFactory = pl2()->getEntityFactory(pocketlistsList::class);
            foreach ($currentPocketRights as $currentPocketId => $rightValue) {
                if ($rightValue == pocketlistsRBAC::RIGHT_ADMIN) {
                    continue;
                }

                $pocket = $pocketFactory->findById($currentPocketId);
                if (!$pocket) {
                    continue;
                }

                $lists = $listFactory->findListsByPocket($pocket, false);
                $items = [];

                if ($lists) {
                    $filter = new pocketlistsStrategyListFilterAndSort($lists);
                    $lists = $filter->filter();

                    foreach ($lists->getNonArchived() as $list) {
                        $items[$list->getId()] = $list->getName();
                    }

                    foreach ($lists->getArchived() as $list) {
                        $items[$list->getId()] = "("._w('archive').") " .$list->getName();
                    }

                    $this->addItem(
                        pocketlistsRBAC::LIST_ITEM,
                        $pocket->getName(),
                        'list',
                        ['items' => $items]
                    );
                }
            }
        }

        /**
         * @event rights.config
         * @param waRightConfig $this Rights setup object
         * @return void
         */
        wa()->event('rights.config', $this);
    }

    /**
     * @param int $contact_id
     *
     * @return array
     */
    public function setDefaultRights($contact_id)
    {
        return [
            pocketlistsRBAC::CAN_CREATE_TODOS    => 1,
            pocketlistsRBAC::CAN_ASSIGN          => 1,
            pocketlistsRBAC::CAN_USE_SHOP_SCRIPT => 1,
        ];
    }

    /**
     * @param int    $contactId
     * @param string $right
     * @param null   $value
     *
     * @return bool
     * @throws waException
     */
    public function setRights($contactId, $right, $value = 0)
    {
        $value = (int) $value;
        if ($pocket_id = (int) str_replace(pocketlistsRBAC::POCKET_ITEM.'.', '', $right)) {
            if ($value == pocketlistsRBAC::RIGHT_NONE) {
                /** @var pocketlistsPocket $pocket */
                $pocket = pl2()->getEntityFactory(pocketlistsPocket::class)->findById($pocket_id);

                /** @var pocketlistsList[] $lists */
                $lists = pl2()->getEntityFactory(pocketlistsList::class)->findListsByPocket($pocket, false);

                /** @var pocketlistsListModel $list */
                foreach ($lists as $list) {
                    $res = $this->right_model->save($contactId, pocketlistsHelper::APP_ID, pocketlistsRBAC::LIST_ITEM.'.'.$list->getId(), $value);
                    if ($res) {
                        self::$rightsCache['list'][$list->getId()] = $value;
                    }
                }
                self::$rightsCache['pocket'][$pocket_id] = $value;
            } else {
                self::$rightsCache['pocket'][$pocket_id] = $value;
            }
        } elseif ($list_id = (int) str_replace(pocketlistsRBAC::LIST_ITEM.'.', '', $right)) {
            self::$rightsCache['list'][$list_id] = $value;
        }

        $this->right_model->save(
            $contactId,
            pocketlistsHelper::APP_ID,
            $right,
            $value
        );

        return true;
    }

    private function setBeginRights()
    {
        $rights = $this->right_model->get($this->userId, pocketlistsHelper::APP_ID);
        foreach ($rights as $_right => $_val) {
            if ($list_id = (int) str_replace(pocketlistsRBAC::LIST_ITEM.'.', '', $_right)) {
                self::$begin_rights['list'][$list_id] = (int) $_val;
            } elseif ($pocket_id = (int) str_replace(pocketlistsRBAC::POCKET_ITEM.'.', '', $_right)) {
                self::$begin_rights['pocket'][$pocket_id] = (int) $_val;
            }
        }
    }

    private function getDiffRights($entity_type)
    {
        $share = [];
        $unshare = [];
        foreach (self::$rightsCache[$entity_type] as $id => $val) {
            if ($val > pocketlistsRBAC::RIGHT_NONE) {
                $share[$id] = $val;
            } else {
                $unshare[$id] = $val;
            }
        }

        return [
            array_diff_assoc($share, self::$begin_rights[$entity_type]),
            array_intersect_key($unshare, self::$begin_rights[$entity_type])
        ];
    }

    private function getDiff($entity_type)
    {
        list($share, $unshare) = $this->getDiffRights($entity_type);

        return array_keys($share + $unshare);
    }

    private function saveLog()
    {
        function logFormat($user_id, $p_sr, $p_uns, $l_sr, $l_uns)
        {
            $logs = [];
            if (!empty($p_sr)) {
                $logs = array_merge($logs, array_map(function ($_p) use ($user_id) {
                    return [
                        'action'      => pocketlistsLog::ACTION_SHARE,
                        'entity_type' => pocketlistsLog::ENTITY_POCKET,
                        'pocket_id'   => $_p,
                        'contact_id'  => $user_id
                    ];
                }, array_keys($p_sr)));
            }
            if (!empty($p_uns) ) {
                $logs = array_merge($logs, array_map(function ($_p) use ($user_id) {
                    return  [
                        'action'      => pocketlistsLog::ACTION_UNSHARE,
                        'entity_type' => pocketlistsLog::ENTITY_POCKET,
                        'pocket_id'   => $_p,
                        'contact_id'  => $user_id
                    ];
                }, array_keys($p_uns)));
            }
            if (!empty($l_sr)) {
                $logs = array_merge($logs, array_map(function ($_l) use ($user_id) {
                    return [
                        'action'      => pocketlistsLog::ACTION_SHARE,
                        'entity_type' => pocketlistsLog::ENTITY_LIST,
                        'list_id'     => $_l,
                        'contact_id'  => $user_id
                    ];
                }, array_keys($l_sr)));
            }
            if (!empty($l_uns)) {
                $logs = array_merge($logs, array_map(function ($_l) use ($user_id) {
                    return [
                        'action'      => pocketlistsLog::ACTION_UNSHARE,
                        'entity_type' => pocketlistsLog::ENTITY_LIST,
                        'list_id'     => $_l,
                        'contact_id'  => $user_id
                    ];
                }, array_keys($l_uns)));
            }
            return $logs;
        }

        list($p_share, $p_unshare) = $this->getDiffRights(pocketlistsRBAC::POCKET_ITEM);
        list($l_share, $l_unshare) = $this->getDiffRights(pocketlistsRBAC::LIST_ITEM);
        if (!empty($p_share) || !empty($p_unshare) || !empty($l_share) || !empty($l_unshare)) {
            $logs = [];
            if ($this->userId < 0) {
                /** for group rights */
                $user_groups_model = new waUserGroupsModel();
                $user_ids = $user_groups_model->getContactIds(-$this->userId);
                if ($user_ids) {
                    foreach ($user_ids as $user_id) {
                        $logs = array_merge($logs, logFormat($user_id, $p_share, $p_unshare, $l_share, $l_unshare));
                    }
                }
            } else {
                /** for user rights */
                $logs = logFormat($this->userId, $p_share, $p_unshare, $l_share, $l_unshare);
            }

            pocketlistsLogService::multipleAdd($logs);
        }
    }
}
