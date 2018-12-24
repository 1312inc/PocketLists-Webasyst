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
     * pocketlistsRightConfig constructor.
     */
    public function __construct()
    {
        $user_id = waRequest::post('user_id', waRequest::TYPE_INT, 0);

        if (!$user_id) {
            $user_id = waRequest::request('id', waRequest::TYPE_INT, 0);
        }

        if ($user_id) {
            $this->user = new waContact(waRequest::post('user_id'));
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
        $this->addItem(pocketlistsRBAC::CAN_CREATE_LISTS, _w('Can create shared to-do lists'), 'checkbox');
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
        foreach (pocketlistsPocketModel::model()->getAllPockets() as $pocket) {
            $items[$pocket->pk] = $pocket->name;
        }

        $this->addItem(
            pocketlistsRBAC::CAN_POCKETLISTS,
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

        $currentRights = $this->user->getRights(pocketlistsHelper::APP_ID, pocketlistsRBAC::CAN_POCKETLISTS);

        // LISTS

        if ($currentRights === 0) {
            $this->addItem(
                'check_and_reload',
                'Please check pocket above and then reload this dialog',
                'header',
                ['hint' => 'Подсказка']
            );
        } else {

            $list_model = new pocketlistsListModel();
            $items = [];
            // todo: только активные? или все подряд?
            $all_lists = $list_model->getAllLists(false);
            usort($all_lists, [$this, 'sort_archive']);
            foreach ($all_lists as $list) {
                $items[$list['id']] = ($list['archived'] ? "("._w('archive').") " : "").$list['name'];
            }

            $this->addItem(
                'list',
                _w('Lists'),
                'list',
                [
                    'items' => $items,
                    //                'hint1' => 'all_checkbox',
                ]
            );
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
            pocketlistsRBAC::CAN_CREATE_LISTS    => 1,
            pocketlistsRBAC::CAN_ASSIGN          => 1,
            pocketlistsRBAC::CAN_USE_SHOP_SCRIPT => 1,
        ];
    }
}
