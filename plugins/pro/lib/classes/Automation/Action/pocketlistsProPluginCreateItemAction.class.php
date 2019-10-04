<?php

/**
 * Class pocketlistsProPluginCreateItemAction
 */
class pocketlistsProPluginCreateItemAction implements pocketlistsProPluginAutomationActionInterface
{
    const DUE_PERIOD_MIN  = 'min';
    const DUE_PERIOD_HOUR = 'hour';
    const DUE_PERIOD_DAY  = 'day';

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $assignedTo;

    /**
     * @var pocketlistsContact
     */
    protected $assignContact;

    /**
     * @var int
     */
    protected $priority = 0;

    /**
     * @var int
     */
    protected $dueIn = 0;

    /**
     * @var string
     */
    protected $duePeriod = self::DUE_PERIOD_DAY;

    /**
     * @var pocketlistsList|null
     */
    protected $list;

    /**
     * @return string
     */
    public function getIdentifier()
    {
        return 'create_item';
    }

    /**
     * Specify data which should be serialized to JSON
     *
     * @link  https://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        return [
            'name'        => $this->name,
            'assigned_to' => $this->assignContact ? $this->assignContact->getId() : null,
            'priority'    => $this->priority,
            'due_in'      => $this->dueIn,
            'list'        => $this->list ? $this->list->getId() : null,
        ];
    }

    /**
     * @return mixed
     */
    public function perform()
    {
        /** @var pocketlistsItemFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsItem::class);

        /** @var pocketlistsItem $item */
        $item = $factory->createNew();
        $item->setName($this->name);
    }

    /**
     * @return string
     */
    public function viewHtml()
    {
        return <<<HTML

HTML;
    }

    /**
     * @return string
     * @throws waException
     */
    public function editHtml()
    {
        $view = wa()->getView();

        /** @var pocketlistsListFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsList::class);
        $users = pocketlistsRBAC::getAccessContacts();
        $app = pl2()->getLinkedApp('shop');
        foreach ($users as $id => $user) {
            if (!$app->userCanAccess($user)) {
                unset($users[$id]);
                continue;
            }
        }

        $view->assign(
            [
                'action' => $this,
                'lists'  => $factory->findLists(),
                'users'  => $users,
            ]
        );

        return $view->fetch(
            wa()->getAppPath('/plugins/pro/templates/actions/automation/actions/createItemEdit.html', 'pocketlists')
        );
    }

    /**
     * @param string $json
     */
    public function load(array $json)
    {
        foreach ($json as $name => $item) {
            switch ($name) {
                case 'name':
                    $this->name = $item;
                    break;

                case 'priority':
                    $this->priority = $item;
                    break;
            }
        }
    }
}
