<?php

/**
 * Class pocketlistsProPluginAutomationService
 */
class pocketlistsProPluginAutomationService
{
    const GROUP_SHOP = 'shop';

    /**
     * @param string $group
     *
     * @return pocketlistsProPluginAutomationSettingsDto[]
     */
    public function getAvailableEventsForGroup($group = self::GROUP_SHOP)
    {
        if (!wa()->appExists('shop')) {
            return [];
        }

        wa('shop');

        /** @see shopSettingsNotificationsAction::getEvents * */
        $actions = (new shopWorkflow())->getAllActions();
        $events = [];
        /**
         * @var shopWorkflowAction $action
         */
        foreach ($actions as $action) {
            $id = $action->getId();
//            $name = $action->getOption('log_record');
//            if (!$name) {
            $name = $action->getName();
//            }

            $eventDto = new pocketlistsProPluginAutomationSettingsDto();
            $eventDto->id = $id;
            $eventDto->name = $name;
            $eventDto->options = $action->getOptions();

            $events['shop.' . $id] = $eventDto;
        }

        $id1312 = uniqid('1312_', true);
        $events['shop.' . $id1312] = new pocketlistsProPluginAutomationSettingsDto();
        $events['shop.' . $id1312]->id = $id1312;
        $events['shop.' . $id1312]->name = _w('== DELETED ACTION ==');

        return $events;
    }

    public function getAvailableRules($group = self::GROUP_SHOP)
    {
        $rules = [
            new pocketlistsProPluginAutomationRuleShopPayment(),
        ];

        return $rules;
    }

    /**
     * @param string $identifier
     * @param array  $data
     *
     * @return pocketlistsProPluginAutomationRuleInterface
     * @throws pocketlistsLogicException
     * @throws pocketlistsProPluginNoShopActionException
     */
    public function createRule($identifier, array $data)
    {
        switch ($identifier) {
            case pocketlistsProPluginAutomationRuleShopAction::IDENTIFIER:
                return (new pocketlistsProPluginAutomationRuleShopAction())->load($data);

            case pocketlistsProPluginAutomationRuleShopAmount::IDENTIFIER:
                return (new pocketlistsProPluginAutomationRuleShopAmount())->load($data);

            case pocketlistsProPluginAutomationRuleShopCustomerGroup::IDENTIFIER:
                return (new pocketlistsProPluginAutomationRuleShopCustomerGroup())->load($data);

            case pocketlistsProPluginAutomationRuleShopPayment::IDENTIFIER:
                return (new pocketlistsProPluginAutomationRuleShopPayment())->load($data);

            case pocketlistsProPluginAutomationRuleShopShipping::IDENTIFIER:
                return (new pocketlistsProPluginAutomationRuleShopShipping())->load($data);

            case pocketlistsProPluginAutomationRuleShopStorefront::IDENTIFIER:
                return (new pocketlistsProPluginAutomationRuleShopStorefront())->load($data);

            case pocketlistsProPluginAutomationRuleShopState::IDENTIFIER:
                return (new pocketlistsProPluginAutomationRuleShopState())->load($data);
        }
    }
}