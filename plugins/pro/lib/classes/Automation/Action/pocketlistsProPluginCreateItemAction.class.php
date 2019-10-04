<?php

/**
 * Class pocketlistsProPluginCreateItemAction
 */
class pocketlistsProPluginCreateItemAction implements pocketlistsProPluginAutomationActionInterface
{
    const DUE_PERIOD_MIN  = 'min';
    const DUE_PERIOD_HOUR = 'hour';
    const DUE_PERIOD_DAY  = 'day';

    const IDENTIFIER = 'create_item';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $note = '';

    /**
     * @var int
     */
    protected $assignedTo = 0;

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
        return self::IDENTIFIER;
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
            'identifier'  => $this->getIdentifier(),
            'name'        => $this->name,
            'note'        => $this->note,
            'assigned_to' => (int)$this->assignedTo,
            'priority'    => (int)$this->priority,
            'due_in'      => (int)$this->dueIn,
            'due_period'  => $this->duePeriod,
            'list'        => $this->list ? (int)$this->list->getId() : null,
        ];
    }

    /**
     * @return mixed
     * @throws waException
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
        $users = [];
        $app = pl2()->getLinkedApp('shop');
        foreach (pocketlistsRBAC::getAccessContacts() as $userId) {
            $user = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId($userId);
            if ($app->userCanAccess($user)) {
                $users[$id] = $user;
            }
        }

        $view->assign(
            [
                'action'     => $this,
                'lists'      => $factory->findLists(),
                'users'      => $users,
                'performer'  => pl2()->getUser(),
                'duePeriods' => [
                    self::DUE_PERIOD_MIN  => _wp('Minutes'),
                    self::DUE_PERIOD_HOUR => _wp('Hours'),
                    self::DUE_PERIOD_DAY  => _wp('Days'),
                ],
            ]
        );

        return $view->fetch(
            wa()->getAppPath('/plugins/pro/templates/actions/automation/actions/createItemEdit.html', pocketlistsHelper::APP_ID)
        );
    }

    /**
     * @param array $json
     *
     * @return pocketlistsProPluginCreateItemAction
     * @throws waException
     */
    public function load(array $json)
    {
        $this->name = ifset($json['name'], '');
        $this->note = ifset($json['note'], '');
        $this->assignedTo = (int)ifset($json['assigned_to'], 0);
        $this->list = !empty($json['list']) ? pl2()->getEntityFactory(pocketlistsList::class)->findById($json['list']) : null;
        $this->dueIn = (int)$json['due_in'];
        $this->duePeriod = $json['due_period'];
        $this->priority = (int)$json['priority'];

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }

    /**
     * @return pocketlistsContact
     */
    public function getAssignContact()
    {
        return $this->assignContact;
    }

    /**
     * @return int
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * @return int
     */
    public function getDueIn()
    {
        return $this->dueIn;
    }

    /**
     * @return string
     */
    public function getDuePeriod()
    {
        return $this->duePeriod;
    }

    /**
     * @return pocketlistsList|null
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $json = json_encode($this, JSON_UNESCAPED_UNICODE);
        if ($json === false) {
            return '';
        }

        return  $json;
    }
}
