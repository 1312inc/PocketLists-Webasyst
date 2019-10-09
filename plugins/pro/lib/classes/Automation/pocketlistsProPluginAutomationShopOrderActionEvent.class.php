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
     * pocketlistsProPluginAutomationShopOrderActionEvent constructor.
     *
     * @param shopOrder          $order
     * @param shopWorkflowAction $action
     */
    public function __construct(shopOrder $order, shopWorkflowAction $action)
    {
        $this->order = $order;
        $this->action = $action;
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
        foreach ($automations as $automation) {
            try {
                pocketlistsLogger::debug(sprintf('automation %d', $automation->getId()));
                pocketlistsLogger::debug($automation);

                if (!$this->automationMatches($automation)) {
                    continue;
                }

                pocketlistsLogger::debug(
                    sprintf('automation %d passed all rules, now execute action', $automation->getId())
                );
                if ($automation->getAction()->execute($this->order)) {
                    $automation
                        ->incExecutionCount()
                        ->setLastExecutionDatetime(date('Y-m-d H:i:s'));

                    pl2()->getEntityFactory(pocketlistsProPluginAutomation::class)->update($automation);
                }
            } catch (Exception $ex) {
                pocketlistsLogger::error(
                    sprintf("PRO: Automation error. %s\n%s", $ex->getMessage(), $ex->getTraceAsString())
                );
            }
        }
    }

    /**
     * @param pocketlistsProPluginAutomation $automation
     *
     * @return bool
     */
    private function automationMatches(pocketlistsProPluginAutomation $automation)
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

            if (!$rule->match($data)) {
                pocketlistsLogger::debug(sprintf('rule %s didnt match', $rule->getIdentifier()));

                return false;
            }
        }

        return true;
    }
}
