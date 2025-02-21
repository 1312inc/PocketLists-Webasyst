<?php

/**
 * Class pocketlistsProPluginCreateItemAction
 */
class pocketlistsProPluginCreateItemAction implements pocketlistsProPluginAutomationActionInterface
{
    const DUE_PERIOD_MIN  = 'minutes';
    const DUE_PERIOD_HOUR = 'hours';
    const DUE_PERIOD_DAY  = 'days';

    const IDENTIFIER = 'create_item';

    const ORDER_ACTION_PERFORMER_ID = -100500;

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
     * @var int
     */
    protected $whenIn = 0;

    /**
     * @var string
     */
    protected $whenPeriod = self::DUE_PERIOD_DAY;

    /**
     * @var pocketlistsList|null
     */
    protected $list;

    /**
     * @var pocketlistsProPluginLabel
     */
    protected $label;

    /**
     * pocketlistsProPluginCreateItemAction constructor.
     */
    public function __construct()
    {
        $this->list = new pocketlistsNullList();
        $this->label = new pocketlistsProPluginLabel();
    }

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
            'identifier' => $this->getIdentifier(),
            'name' => $this->name,
            'note' => $this->note,
            'assigned_to' => (int) $this->assignedTo,
            'priority' => (int) $this->priority,
            'due_in' => (int) $this->dueIn,
            'due_period' => $this->duePeriod,
            'when_in' => (int) $this->whenIn,
            'when_period' => $this->whenPeriod,
            'list' => $this->list ? (int) $this->list->getId() : null,
            'label' => $this->label ? $this->label->getId() : null,
        ];
    }

    /**
     * @param pocketlistsProPluginAutomation $automation
     * @param array                          $params
     *
     * @return bool
     * @throws waException
     */
    public function delay(pocketlistsProPluginAutomation $automation, $params)
    {
        if (!isset($params['order'], $params['action'])) {
            return false;
        }

        $when = (new DateTime())
            ->modify(sprintf('+%s %s', $this->whenIn, $this->whenPeriod))
            ->format('Y-m-d H:i:s');

        /** @var pocketlistsProPluginDelayedAutomationFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsProPluginDelayedAutomation::class);
        $delayed = $factory->createNew()
            ->setAutomation($automation)
            ->setApplyDatetime($when)
            ->setEventData(
                [
                    'order_id' => $params['order']->getId(),
                    'action_id' => $params['action']->getId(),
                    'action_performer_contact_id' => wa()->getUser()->getId(),
                ]
            );
        $factory->insert($delayed);

        pocketlistsLogger::debug(
            sprintf('delayed automation %d saved', $delayed->getId())
        );

        return true;
    }

    /**
     * @param pocketlistsProPluginAutomation                   $automation
     * @param pocketlistsProPluginAutomationShopOrderActionDto $params
     *
     * @return mixed
     * @throws waException
     */
    public function execute(pocketlistsProPluginAutomation $automation, $params)
    {
        /** @var pocketlistsItemFactory $factory */
        $factory = pl2()->getEntityFactory(pocketlistsItem::class);

        $itemName = $this->replaceOrderVars($this->name, $params->order);
        $itemNote = null;
        if ($this->note) {
            $itemNote = $this->replaceOrderVars($this->note, $params->order);
        }

        if ($this->assignedTo == self::ORDER_ACTION_PERFORMER_ID && $params->actionPerformerContactId) {
            $assigned = $params->actionPerformerContactId;
        } elseif ($this->assignedTo > 0) {
            $assigned = $this->assignedTo;
        } else {
            $assigned = null;
        }

        /** @var pocketlistsItem $item */
        $item = $factory->createNew();
        $item
            ->setContactId(pocketlistsBot::PL2BOT_ID)
            ->setName($itemName)
            ->setNote($itemNote)
            ->setPriority($this->priority)
            ->setAssignedContactId($assigned)
            ->setList($this->list)
            ->setCreateDatetime(date('Y-m-d H:i:s'));

        if ($this->dueIn) {
            if ($this->duePeriod === self::DUE_PERIOD_DAY) {
                $due = (new DateTime())
                    ->modify(sprintf('%s %s', $this->dueIn, $this->duePeriod))
                    ->format('Y-m-d 00:00:00');
                $item->setDueDate($due);
            } else {
                $due = (new DateTime())->modify(sprintf('%s %s', $this->dueIn, $this->duePeriod));
                $item
                    ->setDueDate($due->format('Y-m-d 00:00:00'))
                    ->setDueDatetime($due->format('Y-m-d H:i:s'));
            }
        }

        if ($this->label->getId()) {
            $this->label->assignToItem($item);
        }

        if (pl2()->getEntityFactory(pocketlistsItem::class)->insert($item)) {
            pl2()->getEntityFactory(pocketlistsItemLink::class)->createFromDataForItem(
                $item,
                [
                    'app' => pocketlistsAppLinkShop::APP,
                    'entity_type' => pocketlistsAppLinkShop::TYPE_ORDER,
                    'entity_id' => $params->order->getId(),
                ],
                false
            );

            pl2()->getEventDispatcher()->dispatch(
                new pocketlistsEventItemsSave(
                    pocketlistsEventStorage::ITEM_INSERT,
                    $item,
                    ['list' => $this->list, 'assign_contact_id' => $assigned]
                )
            );

            pocketlistsLogger::debug(
                sprintf('item %d created from automation action %s', $item->getId(), $this->getIdentifier())
            );

            return $item;
        }

        pocketlistsLogger::debug(
            sprintf('item %d failed to create from automation action %s', $item->getId(), $this->getIdentifier())
        );

        return false;
    }

    /**
     * @return string
     * @throws waException
     */
    public function viewHtml()
    {
        $view = wa()->getView();

        $assign = 0;
        if ($this->assignedTo > 0) {
            $assign = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId($this->assignedTo)->getName();
        } elseif ($this->assignedTo == self::ORDER_ACTION_PERFORMER_ID) {
            $assign = _wp('Order action performer');
        }

        $view->assign(
            [
                'assign' => $assign,
                'action' => $this,
                'due' => !empty($this->dueIn) ? sprintf_wp('%d %s', $this->dueIn, $this->duePeriod) : 0,
                'when' => !empty($this->whenIn) ? sprintf_wp('%d %s', $this->whenIn, $this->whenPeriod) : 0,
            ]
        );

        return $view->fetch(
            wa()->getAppPath(
                sprintf(
                    '/plugins/pro/templates/actions%s/automation/actions/createItemView.html',
                    pl2()->getUI2TemplatePath()
                ),
                pocketlistsHelper::APP_ID
            )
        );
    }

    /**
     * @return string
     * @throws waException
     */
    public function editHtml()
    {
        $view = wa()->getView();

        /** @var pocketlistsListFactory $factoryList */
        $factoryList = pl2()->getEntityFactory(pocketlistsList::class);
        $users = [];
        $app = pl2()->getLinkedApp('shop');
        foreach (pocketlistsRBAC::getAccessContacts() as $userId) {
            $user = pl2()->getEntityFactory(pocketlistsContact::class)->createNewWithId($userId);
            if ($app->userCanAccess($user)) {
                $users[$userId] = $user;
            }
        }

        /** @var pocketlistsProPluginLabelFactory $factoryLabel */
        $factoryLabel = pl2()->getEntityFactory(pocketlistsProPluginLabel::class);
        /** @var pocketlistsProPluginCreateItemActionLabelDto[] $labels */
        $labels = [];
        foreach ($factoryLabel->findAll() as $label) {
            $labels[] = new pocketlistsProPluginCreateItemActionLabelDto(
                $label->getId(),
                $label->getName(),
                $label->getColor()
            );
        }

        /** @var pocketlistsPocketFactory $pocketFactory */
        $pocketFactory = pl2()->getEntityFactory(pocketlistsPocket::class);
        $allPockets = $pocketFactory->findAllForUser();
        /** @var pocketlistsListDetailsListsDto[] $lists */
        $lists = [];
        foreach ($allPockets as $pocket) {
            foreach ($pocket->getUserLists() as $list) {
                $lists[] = new pocketlistsProPluginCreateItemActionListDto(
                    $list->getId(),
                    $list->getNameParsed(),
                    $pocket->getName()
                );
            }
        }

        $view->assign(
            [
                'action' => $this,
                'lists' => $lists,
                'labels' => $labels,
                'users' => $users,
                'performer' => pl2()->getUser(),
                'duePeriods' => [
                    self::DUE_PERIOD_MIN => _wp('Minutes'),
                    self::DUE_PERIOD_HOUR => _wp('Hours'),
                    self::DUE_PERIOD_DAY => _wp('Days'),
                ],
                'cronMessage' => $this->getCronMessage(pocketlistsProPluginCronManager::APPLY_DELAYED_AUTOMATIONS),
            ]
        );

        return $view->fetch(
            wa()->getAppPath(
                sprintf(
                    '/plugins/pro/templates/actions%s/automation/actions/createItemEdit.html',
                    pl2()->getUI2TemplatePath()
                ),
                pocketlistsHelper::APP_ID
            )
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
        $this->assignedTo = (int) ifset($json['assigned_to'], 0);
        $this->list = !empty($json['list'])
            ? pl2()->getEntityFactory(pocketlistsList::class)->findById($json['list'])
            : null;
        $this->dueIn = (int) ifset($json['due_in'], 0);
        $this->duePeriod = ifset($json['due_period'], self::DUE_PERIOD_MIN);
        if (isset($json['when_type']) && $json['when_type'] == 0) {
            $json['when_in'] = 0;
        }
        $this->whenIn = (int) ifset($json['when_in'], 0);
        $this->whenPeriod = ifset($json['when_period'], self::DUE_PERIOD_MIN);
        $this->priority = (int) ifset($json['priority'], pocketlistsItem::PRIORITY_NORM);

        if (!$this->list instanceof pocketlistsList) {
            $this->list = new pocketlistsNullList();
        }
        $labelId = (int) ifset($json['label'], 0);
        if ($labelId) {
            /** @var pocketlistsProPluginLabelFactory $labelRep */
            $labelRep = pl2()->getEntityFactory(pocketlistsProPluginLabel::class);
            $actionLabel = $labelRep->findById($labelId);
            if ($actionLabel instanceof pocketlistsProPluginLabel) {
                $this->label = $actionLabel;
            }
        }

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
     * @return int
     */
    public function getWhenIn()
    {
        return $this->whenIn;
    }

    /**
     * @return string
     */
    public function getWhenPeriod()
    {
        return $this->whenPeriod;
    }

    /**
     * @return pocketlistsList|null
     */
    public function getList()
    {
        return $this->list;
    }

    /**
     * @return pocketlistsProPluginLabel
     */
    public function getLabel()
    {
        return $this->label;
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

        return (string) $json;
    }

    /**
     * @param string    $str
     * @param shopOrder $order
     *
     * @return mixed
     */
    private function replaceOrderVars($str, shopOrder $order)
    {
        $orderParams = $order->params;
        $lastLog = $order->log;
        if (is_array($lastLog)) {
            $lastLog = reset($lastLog);
        }

        return str_replace(
            [
                '{$customer_name}',
                '{$tracking_number}',
                '{$order_id}',
                '{$order_amount}',
                '{$shipping_cost}',
                '{$shipping_address}',
                '{$order_source}',
                '{$order_item_names}',
                '{$customer_phone}',
                '{$customer_email}',
                '{$order_comment}',
                '{$action_comment}',
            ],
            [
                $order->contact->getName(),
                ifset($orderParams, 'tracking_number', _wp('No tracking number')),
                $order->id_str,
                waCurrency::format('%2{s}', $order->total, $order->currency),
                waCurrency::format('%2{s}', $order->shipping, $order->currency),
                $order->shipping_address_text,
                $order->source,
                implode(
                    "\n",
                    array_map(
                        static function ($item) {
                            return sprintf('- %s', $item['name']);
                        },
                        $order->items
                    )
                ),
                $order->contact->get('phone', 'default'),
                $order->contact->get('email', 'default'),
                $order->comment,
                isset($lastLog['text']) ? $lastLog['text'] : '',
            ],
            $str
        );
    }

    /**
     * @param string $cronJonName
     *
     * @return string
     * @throws waException
     */
    private function getCronMessage($cronJonName)
    {
        $cronCommandInfo = _wp(
                'Webasyst Cloud: launch the Cloud app &gt; Add Cron job &gt; '
            ) . ' <code class="highlighted black">php cli.php [Pocket Lists] <b>proPluginApplyDelayedAutomations</b></code>' .
            '<BR><BR>' .
            _wp('Own server:') . ' <code class="highlighted">' . pocketlistsProPlugin::getInstance()
                ->getCronManager()
                ->getCronJobs($cronJonName) . '</code>';
        $cronMessage = '<i class="icon10 exclamation"></i>' .
            _wp(
                'Delayed actions require CRON to be configured for the Pocket Lists PRO plugin, which is not the case for your installation. Cron the following command every 10 minutes:'
            ) .
            '<br><br>' .
            $cronCommandInfo;

        $lastCron = pocketlistsProPlugin::getInstance()->getCronManager()->getLastRunCronJob($cronJonName);
        if ($lastCron) {
            $cronMessage = '<i class="icon10 yes"></i>' . sprintf_wp(
                    'Last Cron execution time: %s',
                    waDateTime::format('humandatetime', $lastCron->format('Y-m-d H:i:s'), date_default_timezone_get())
                ) .
                ' <a href="javascript:alert(\'' . strip_tags(
                    str_replace('<BR><BR>', '\n\n', $cronCommandInfo)
                ) . '\');">' . _wp('See Cron command') . '</a>';
        }

        return $cronMessage;
    }
}
