<?php

/**
 * Class pocketlistsProPluginAutomationShopOrderActionEvent
 */
class pocketlistsProPluginAutomationShopOrderActionEvent implements pocketlistsProPluginAbstractAutomationEventInterface
{
    const NAME = 'order_action';

    /**
     * @var shopOrder
     */
    private $order;

    /**
     * @var shopWorkflowAction
     */
    private $action;

    /**
     * @var string
     */
    private $failedRule;

    /**
     * @var pocketlistsProPluginSettings
     */
    private $settings;

    /**
     * pocketlistsProPluginAutomationShopOrderActionEvent constructor.
     *
     * @param shopOrder          $order
     * @param shopWorkflowAction $action
     */
    public function __construct(shopOrder $order, shopWorkflowAction $action)
    {
        $this->order = $order;
        $this->action = $action;
        $this->settings = new pocketlistsProPluginSettings();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'shop';
    }

    /**
     * @return void
     * @throws waException
     */
    public function applyAutomations()
    {
        /** @var pocketlistsProPluginAutomationFactory $f */
        $f = pl2()->getEntityFactory(pocketlistsProPluginAutomation::class);
        $automations = $f->findByEventAndType(self::NAME, 'shop');

        pocketlistsLogger::debug(sprintf('run %d automations for shop order actions', count($automations)));
        /** @var pocketlistsProPluginAutomation $automation */
        foreach ($automations as $automation) {
            if (!$automation->isValid()) {
                continue;
            }

            try {
                pocketlistsLogger::debug(sprintf('automation %d', $automation->getId()));
                if (waSystemConfig::isDebug()) {
                    pocketlistsLogger::debug(pl2()->getHydrator()->extract($automation));
                }

                if (!$automation->isEnabled()) {
                    pocketlistsLogger::debug(sprintf('Automation %d is disabled', $automation->getId()));

                    continue;
                }

                if ($automation->getAction()->getWhenIn()) {
                    $this->delayAutomation($automation);
                } else {
                    $this->executeAutomation($automation);
                }
            } catch (Exception $exception) {
                pocketlistsLogger::error(sprintf("%s\n%s", $exception->getMessage(), $exception->getTraceAsString()));
            }
        }
    }

    /**
     * @param pocketlistsProPluginAutomation $automation
     * @param array                          $params
     *
     * @return pocketlistsProPluginAutomationActionEventResult
     */
    public function executeAutomation(pocketlistsProPluginAutomation $automation, array $params = [])
    {
        $result = new pocketlistsProPluginAutomationActionEventResult();

        try {
            if (!$this->automationMatches($automation, false)) {
                return $result;
            }

            pocketlistsLogger::debug(
                sprintf('automation %d passed all rules, now execute action', $automation->getId())
            );

            $params = new pocketlistsProPluginAutomationShopOrderActionDto(
                $this->order,
                $params['action_performer_contact_id'] ?? wa()->getUser()->getId()
            );
            $item = $automation->getAction()->execute($automation, $params);
            if ($item instanceof pocketlistsItem) {
                $automation
                    ->incExecutionCount()
                    ->setLastExecutionDatetime(date('Y-m-d H:i:s'));

                pl2()->getEntityFactory(pocketlistsProPluginAutomation::class)->update($automation);

                if ($this->settings->isRunLogs()) {
                    $msg = sprintf_wp(
                        'Pocket Lists: new to-do "%s" (id: %d) based on order action %s (%s)%s.',
                        $item->getName(),
                        $item->getId(),
                        $this->action->getName(),
                        $this->action->getId(),
                        wa()->getEnv() === 'cli' ? ' - delayed': ''
                    );
                    $this->order->log($msg);
                }
            }

            $result->data = $item;
            $result->status = true;

            return $result;
        } catch (Exception $ex) {
            pocketlistsLogger::error(
                sprintf("PRO: Automation error. %s\n%s", $ex->getMessage(), $ex->getTraceAsString())
            );
        }

        return $result;
    }

    /**
     * @param pocketlistsProPluginAutomation $automation
     *
     * @return bool
     */
    public function delayAutomation(pocketlistsProPluginAutomation $automation)
    {
        try {
            if (!$this->automationMatches($automation)) {
                return false;
            }

            pocketlistsLogger::debug(
                sprintf('automation %d passed all rules, now save delayed', $automation->getId())
            );

            $automation->getAction()->delay(
                $automation,
                [
                    'order' => $this->order,
                    'action' => $this->action,
                ]
            );

            return true;
        } catch (Exception $ex) {
            pocketlistsLogger::error(
                sprintf("PRO: Automation error. %s\n%s", $ex->getMessage(), $ex->getTraceAsString())
            );
        }

        return false;
    }

    /**
     * @return string
     */
    public function getFailedRule()
    {
        return $this->failedRule;
    }

    /**
     * @param pocketlistsProPluginAutomation $automation
     * @param bool                           $skipDelayed
     *
     * @return bool
     */
    private function automationMatches(pocketlistsProPluginAutomation $automation, $skipDelayed = true)
    {
        foreach ($automation->getRules() as $rule) {
            pocketlistsLogger::debug(sprintf('rule %s', $rule->getIdentifier()));

            switch ($rule->getIdentifier()) {
                case pocketlistsProPluginAutomationRuleShopAction::IDENTIFIER:
                    $data = $this->action;
                    break;

                default:
                    $data = $this->order;
            }

            if (!$rule->skipDelayed($skipDelayed)->match($data)) {
                $this->failedRule = $rule->getIdentifier();
                pocketlistsLogger::debug(sprintf('rule %s didnt match', $rule->getIdentifier()));

                return false;
            }
        }

        return true;
    }
}
