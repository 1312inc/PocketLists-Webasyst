<?php

/**
 * Class pocketlistsRightConfig
 */
class pocketlistsRightConfig extends waRightConfig
{
    /**
     * @var waContact
     */
    private $user;

    /**
     * @var array
     */
    private static $rightsCache = [
        'lists'   => [],
        'pockets' => [],
    ];

    /**
     * pocketlistsRightConfig constructor.
     */
    public function __construct()
    {
        $user_id = waRequest::post('user_id', 0, waRequest::TYPE_INT);

        if (!$user_id) {
            $user_id = waRequest::request('id', 0, waRequest::TYPE_INT);
        }

        if ($user_id) {
            $this->user = new waContact($user_id);
        } else {
            $this->user = new waContact();
        }

        parent::__construct();
    }

    /**
     * @throws waDbException
     */
    public function init()
    {
        $this->addItem(pocketlistsRBAC::CAN_CREATE_TODOS, _w('Can create to-dos to self'), 'always_enabled');
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
        $pm = new pocketlistsPocketModel();
        foreach ($pm->getAllPockets() as $pocket) {
            $items[$pocket->pk] = $pocket->name;
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

        $currentPocketRights = $this->user->getRights(pocketlistsHelper::APP_ID, pocketlistsRBAC::POCKET_ITEM.'.%');

        // LISTS

        if (empty($currentPocketRights)) {
            $this->addItem(
                '',
                _w('To setup access rights by pocket, set Limited access for at least one pocket'),
                'header',
                ['hint1' => 'Подсказка']
            );
        } else {
            $lm = new pocketlistsListModel();
            foreach ($currentPocketRights as $currentPocketId => $rightValue) {
                if ($rightValue == pocketlistsRBAC::RIGHT_ADMIN) {
                    continue;
                }

                $pocket = $pm->findByPk($currentPocketId);
                $lists = $lm->getLists(false, $currentPocketId);
                $items = [];

                if ($lists) {
                    usort($lists, [$this, 'sort_archive']);
                    foreach ($lists as $list) {
                        $items[$list['id']] = ($list['archived'] ? "("._w('archive').") " : "").$list['name'];
                    }

                    $this->addItem(
                        pocketlistsRBAC::LIST_ITEM,
                        $pocket->name,
                        'list',
                        [
                            'items' => $items,
                            //                'hint1' => 'all_checkbox',
                        ]
                    );
                }
            }
        }
    }

    /**
     * @param $a
     * @param $b
     *
     * @return bool
     */
    private function sort_archive($a, $b)
    {
        return $a['archived'] > $b['archived'];
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
     * @param int    $contact_id
     * @param string $right
     * @param null   $value
     *
     * @return bool
     *
     * @throws waDbException
     */
    public function setRights($contact_id, $right, $value = null)
    {
        $right_model = new waContactRightsModel();

        if (strpos($right, pocketlistsRBAC::POCKET_ITEM.'.') === 0) {
            $pocketId = (int)str_replace(pocketlistsRBAC::POCKET_ITEM.'.', '', $right);
            if ($value == pocketlistsRBAC::RIGHT_NONE) {
                $lists = pocketlistsListModel::model()->getLists(false, $pocketId);
                /** @var pocketlistsListModel $list */
                foreach ($lists as $list) {
                    if ($right_model->save(
                        $contact_id,
                        pocketlistsHelper::APP_ID,
                        pocketlistsRBAC::LIST_ITEM.'.'.$list->pk,
                        $value
                    )) {
                        self::$rightsCache['lists'][$list->pk] = true;
                    }
                }
            }
        }

        if (strpos($right, pocketlistsRBAC::LIST_ITEM.'.') === 0) {
            $listId = (int)str_replace(pocketlistsRBAC::LIST_ITEM.'.', '', $right);
            if (isset(self::$rightsCache['lists'][$listId])) {
                return true;
            }
        }

        $right_model->save(
            $contact_id,
            pocketlistsHelper::APP_ID,
            $right,
            $value
        );

        return true;
    }
}
